<?php

namespace App\Http\Controllers\Api;

use App\Models\FieldObservation;
use App\Http\Resources\FieldObservationResource;
use App\Notifications\FieldObservationMovedToPending;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PendingFieldObservationsBatchController
{
    use AuthorizesRequests;

    /**
     * Move field observations to pending.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function store()
    {
        request()->validate([
            'field_observation_ids' => [
                'required', 'array', 'min:1',
            ],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $fieldObservations = FieldObservation::with([
            'observation.creator', 'observation.taxon.curators.roles',
        ])->whereIn('id', request('field_observation_ids'))->get();

        $fieldObservations->each(function ($fieldObservation) {
            $this->authorize('moveToPending', $fieldObservation);
        });

        $fieldObservations->moveToPending();

        $fieldObservations->each(function ($fieldObservation) {
            $this->logActivity($fieldObservation);
            $this->notifyCreator($fieldObservation);
        });

        return FieldObservationResource::collection($fieldObservations);
    }

    /**
     * Log approved activity for field observation.
     *
     * @param  \App\Models\FieldObservation  $fieldObservation
     * @return void
     */
    protected function logActivity(FieldObservation $fieldObservation)
    {
        activity()->performedOn($fieldObservation)
           ->causedBy(auth()->user())
           ->withProperty('reason', request('reason'))
           ->log('moved_to_pending');
    }

    /**
     * Notify the creator that the observation is approved.
     *
     * @param  \App\Models\FieldObservation  $fieldObservation
     * @return void
     */
    private function notifyCreator(FieldObservation $fieldObservation)
    {
        $user = auth()->user();

        if (! $user->is($fieldObservation->observation->creator)) {
            optional($fieldObservation->observation->creator)->notify(
                new FieldObservationMovedToPending($fieldObservation, $user)
            );
        }
    }
}
