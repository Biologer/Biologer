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

        $fieldObservations = FieldObservation::approvable()->with([
            'observation.taxon.curators.roles', 'photos',
        ])->whereIn('id', request('field_observation_ids'))->get();

        $fieldObservations->each(function ($fieldObservation) {
            $this->authorize('approve', $fieldObservation);
        });

        $fieldObservations->approve();

        return FieldObservationResource::collection($fieldObservations);
    }
}
