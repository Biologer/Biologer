<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFieldObservation;
use App\Http\Requests\UpdateFieldObservation;
use App\Http\Resources\FieldObservationResource;

class FieldObservationsController extends Controller
{
    /**
     * Get field observations.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $query = FieldObservation::with([
             'observation.taxon', 'photos', 'activity.causer',
         ])->filter(request())->orderBy('id');

        if (request()->has('page')) {
            return FieldObservationResource::collection(
                 $query->paginate(request('per_page', 15))
             );
        }

        return FieldObservationResource::collection($query->get());
    }

    /**
     * Add new field observation.
     *
     * @param  \App\Http\Requests\StoreFieldObservation  $form
     * @return \App\Http\Resources\FieldObservationResource
     */
    public function store(StoreFieldObservation $form)
    {
        return new FieldObservationResource($form->save());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return \App\Http\Resources\FieldObservationResource
     */
    public function show(FieldObservation $fieldObservation)
    {
        return new FieldObservationResource($fieldObservation);
    }

    /**
     * Update field observation.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @param  \App\Http\Requests\UpdateFieldObservation  $form
     * @return \App\Http\Resources\FieldObservationResource
     */
    public function update(FieldObservation $fieldObservation, UpdateFieldObservation $form)
    {
        return new FieldObservationResource($form->save($fieldObservation));
    }

    /**
     * Delete field observation.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(FieldObservation $fieldObservation)
    {
        $fieldObservation->delete();

        return response()->json(null, 204);
    }
}
