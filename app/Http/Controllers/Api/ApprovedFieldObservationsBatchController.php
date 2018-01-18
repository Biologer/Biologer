<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\ApprovableFieldObservation;
use App\Http\Resources\FieldObservation as FieldObservationResource;
use Illuminate\Validation\ValidationException;

class ApprovedFieldObservationsBatchController extends Controller
{
    public function store()
    {
        request()->validate([
            'field_observation_ids' => [
                'required', 'array', 'min:1', new ApprovableFieldObservation
            ]
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
