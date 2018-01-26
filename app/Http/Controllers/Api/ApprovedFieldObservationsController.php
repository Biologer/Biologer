<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Rules\ApprovableFieldObservation;
use App\Http\Resources\FieldObservationResource;

class ApprovedFieldObservationsController extends Controller
{
    /**
     * Approve field observation.
     *
     * @return \App\Http\Resources\FieldObservationResource
     */
    public function store()
    {
        request()->validate([
            'field_observation_id' => [
                'required',
                new ApprovableFieldObservation(),
            ],
        ]);

        $fieldObservation = FieldObservation::with([
            'observation.taxon.curators.roles', 'photos',
        ])->findOrFail(request('field_observation_id'));

        $this->authorize('approve', $fieldObservation);

        return new FieldObservationResource($fieldObservation->approve());
    }
}
