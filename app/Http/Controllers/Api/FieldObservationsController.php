<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Http\Forms\NewFieldObservationForm;
use App\Http\Forms\FieldObservationUpdateForm;
use App\Http\Resources\FieldObservation as FieldObservationResource;

class FieldObservationsController extends Controller
{
    /**
     * Get field observations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function index(Request $request)
     {
         $query = FieldObservation::filter($request)->orderBy('id');

         if ($request->input('all', false)) {
             return FieldObservationResource::collection($quary->get());
         }

         return FieldObservationResource::collection(
             $query->paginate($request->input('per_page', 15))
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
        $fieldObservation = FieldObservation::findOrFail($id);

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
        $fieldObservation = FieldObservation::findOrFail($id);

        $fieldObservation->observation->delete();
        $fieldObservation->delete();

        return response()->json(null, 204);
    }
}
