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

         if ($request->has('all')) {
             return FieldObservationResource::collection($query->get());
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
        return new FieldObservationResource($form->save());
    }

    /**
     * Update field observation.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, FieldObservationUpdateForm $form)
    {
        return new FieldObservationResource(
            $form->save(FieldObservation::findOrFail($id))
        );
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
