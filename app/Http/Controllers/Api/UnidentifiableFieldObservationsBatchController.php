<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use App\Http\Resources\FieldObservationResource;
use App\Notifications\FieldObservationMarkedUnidentifiable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UnidentifiableFieldObservationsBatchController
{
    use AuthorizesRequests;

    /**
     * Mark multiple field observations as unidentifiable.
     *
     * @return \App\Http\Resources\FieldObservationResource
     */
    public function store()
    {
        request()->validate([
            'field_observation_ids' => ['required', 'array', 'min:1'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $fieldObservations = $this->getFieldObservations();

        $fieldObservations->each(function ($fieldObservation) {
            $this->authorize('markAsUnidentifiable', $fieldObservation);
        });

        $fieldObservations->markAsUnidentifiable();

        $fieldObservations->each(function ($fieldObservation) {
            $this->logActivity($fieldObservation);
            $this->notifyCreator($fieldObservation);
        });

        return FieldObservationResource::collection($fieldObservations);
    }

    /**
     * Get field observation to mark unidentifiable.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getFieldObservations()
    {
        return FieldObservation::with([
            'observation.creator', 'observation.taxon.curators.roles',
        ])->whereIn('id', request('field_observation_ids'))->get();
    }

    /**
     * Log marked unidentified activity for field observation.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return void
     */
    protected function logActivity(FieldObservation $fieldObservation)
    {
        activity()->performedOn($fieldObservation)
           ->causedBy(auth()->user())
           ->withProperty('reason', request('reason'))
           ->log('marked_unidentifiable');
    }

    /**
     * Notify the creator that the observation is marked as unidentifiable.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return void
     */
    private function notifyCreator(FieldObservation $fieldObservation)
    {
        $user = auth()->user();

        if (! $user->is($fieldObservation->observation->creator)) {
            $fieldObservation->observation->creator->notify(
                new FieldObservationMarkedUnidentifiable($fieldObservation, $user)
            );
        }
    }
}
