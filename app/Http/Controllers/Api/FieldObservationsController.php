<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Http\Forms\NewFieldObservationForm;
use App\Http\Forms\FieldObservationUpdateForm;

class FieldObservationsController extends Controller
{
    /**
     * Add new field observation.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(NewFieldObservationForm $form)
    {
        return response()->json($form->save(), 201);
    }

    /**
     * Update field observation.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, FieldObservationUpdateForm $form)
    {
        $observation = FieldObservation::findOrFail($id);

        $form->save($observation);

        return response()->json($observation, 200);
    }
}
