<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Forms\NewFieldObservationForm;

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
}
