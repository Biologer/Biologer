<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\ApprovableFieldObservation;
use App\Http\Resources\FieldObservation as FieldObservationResource;

class ApprovedFieldObservationsController extends Controller
{
    public function store()
    {
        request()->validate([
            'field_observation_id' => [
                'required',
                new ApprovableFieldObservation
            ]
        ]);

        $fieldObservation = FieldObservation::findOrFail(request('field_observation_id'));

        $this->authorize('approve', $fieldObservation);

        return new FieldObservationResource($fieldObservation->approve());
    }
}
