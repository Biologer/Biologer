<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\ApprovableFieldObservation;
use App\Http\Resources\FieldObservation as FieldObservationResource;

class ApprovedFieldObservationsBatchController extends Controller
{
    public function store()
    {
        request()->validate([
            'field_observation_ids' => 'required|array|min:1',
            'field_observation_ids.*' => [
                'required',
                new ApprovableFieldObservation
            ]
        ]);

        $fieldObservations = FieldObservation::with(['observation.taxon.curators'])
            ->whereIn('id', request('field_observation_ids'))
            ->get();

        $fieldObservations->each(function ($fieldObservation) {
            $this->authorize('approve', $fieldObservation);
        });

        $fieldObservations->approve();

        return FieldObservationResource::collection($fieldObservations);
    }
}
