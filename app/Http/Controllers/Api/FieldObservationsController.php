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

         if ($request->has('page')) {
             return FieldObservationResource::collection(
                 $query->paginate($request->input('per_page', 15))
             );
         }

         return FieldObservationResource::collection($query->get());
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
     * Display the specified resource.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(FieldObservation $fieldObservation)
    {
        return new FieldObservationResource($fieldObservation);
    }

    /**
     * Update field observation.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(FieldObservation $fieldObservation, FieldObservationUpdateForm $form)
    {
        return new FieldObservationResource(
            $form->save($fieldObservation)
        );
    }

    /**
     * Delete field observation.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(FieldObservation $fieldObservation)
    {
        $fieldObservation->observation->delete();
        $fieldObservation->delete();

        return response()->json(null, 204);
    }
}
