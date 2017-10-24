<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Http\Forms\NewFieldObservationForm;
use App\Http\Forms\FieldObservationUpdateForm;

class FieldObservationsController extends Controller
{
    /**
     * Get field observations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (request('all')) {
            return FieldObservation::with('observation')->get();
        }

        return FieldObservation::with('observation')->paginate(
            request('per_page', 15)
        );
    }

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
        $fieldObservation = FieldObservation::with('observation')->findOrFail($id);

        $form->save($fieldObservation);

        return response()->json($fieldObservation, 200);
    }

    /**
     * Delete field observation.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $fieldObservation = FieldObservation::with('observation')->findOrFail($id);

        $fieldObservation->observation->delete();
        $fieldObservation->delete();

        return response()->json(null, 204);
    }
}
