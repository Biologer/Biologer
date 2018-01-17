<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FieldObservation as FieldObservationResource;

class UnidentifiableFieldObservationsBatchController extends Controller
{
    public function store()
    {
        request()->validate([
            'field_observation_ids' => 'required|array|min:1',
            'field_observation_ids.*' => [
                'required',
            ]
        ]);

        $fieldObservations = FieldObservation::with([
            'observation.taxon.curators.roles', 'photos'
        ])->whereIn('id', request('field_observation_ids'))->get();

        $fieldObservations->each(function ($fieldObservation) {
            $this->authorize('markAsUnidentifiable', $fieldObservation);
        });

        $fieldObservations->markAsUnidentifiable();

        return FieldObservationResource::collection($fieldObservations);
    }
}
