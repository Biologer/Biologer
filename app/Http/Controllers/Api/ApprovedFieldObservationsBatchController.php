<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Http\Resources\FieldObservationResource;
use App\Notifications\FieldObservationApproved;
use App\Rules\ApprovableFieldObservation;

class ApprovedFieldObservationsBatchController extends Controller
{
    /**
     * Approve multiple field observations.
     *
     * @return \App\Http\Resources\FieldObservationResource
     */
    public function store()
    {
        request()->validate([
            'field_observation_ids' => [
                'required', 'array', 'min:1', new ApprovableFieldObservation,
            ],
        ]);

        $fieldObservations = $this->getFieldObservations();

        $fieldObservations->each(function ($fieldObservation) {
            $this->authorize('approve', $fieldObservation);
        });

        $fieldObservations->approve();

        $fieldObservations->each(function ($fieldObservation) {
            $this->logActivity($fieldObservation);
            $this->notifyCreator($fieldObservation);
        });

        return FieldObservationResource::collection($fieldObservations);
    }

    /**
     * Get field observation to approve.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getFieldObservations()
    {
        return FieldObservation::approvable()->with([
            'observation.creator', 'observation.taxon.curators.roles',
        ])->whereIn('id', request('field_observation_ids'))->get();
    }

    /**
     * Log approved activity for field observation.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return void
     */
    protected function logActivity(FieldObservation $fieldObservation)
    {
        activity()->performedOn($fieldObservation)
           ->causedBy(auth()->user())
           ->log('approved');
    }

    /**
     * Notify the creator that the observation is approved.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return void
     */
    private function notifyCreator(FieldObservation $fieldObservation)
    {
        $user = auth()->user();

        if (! $user->is($fieldObservation->observation->creator)) {
            $fieldObservation->observation->creator->notify(
                new FieldObservationApproved($fieldObservation, $user)
            );
        }
    }
}
