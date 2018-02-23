<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Rules\ApprovableFieldObservation;
use App\Http\Resources\FieldObservationResource;

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
            'observation.taxon.curators.roles', 'photos',
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
}
