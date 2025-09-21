<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreTimedCountObservation;
use App\Http\Requests\UpdateTimedCountObservation;
use App\Http\Resources\TimedCountObservationResource;
use App\TimedCountObservation;
use Illuminate\Http\Request;

class TimedCountObservationsController
{
    /**
     * Get timed count observations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $result = TimedCountObservation::with([
             'fieldObservations',
        ])->filter($request)->isFieldObservation()->orderBy('id')->paginate($request->get('per_page', 15));

        return TimedCountObservationResource::collection($result);
    }

    /**
     * Add new timed count observation.
     *
     * @param  \App\Http\Requests\StoreFieldObservation  $form
     * @return \App\Http\Resources\TimedCountObservationResource
     */
    public function store(StoreTimedCountObservation $form)
    {
        return new TimedCountObservationResource($form->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FieldObservation  $timedCountObservation
     * @return \App\Http\Resources\TimedCountObservationResource
     */
    public function show(TimedCountObservation $timedCountObservation)
    {
        return new TimedCountObservationResource($timedCountObservation);
    }

    /**
     * Update timed count observation.
     *
     * @param  \App\TimedCountObservation  $timedCountObservation
     * @param  \App\Http\Requests\UpdateTimedCountObservation  $form
     * @return \App\Http\Resources\TimedCountObservationResource
     */
    public function update(TimedCountObservation $timedCountObservation, UpdateTimedCountObservation $form)
    {
        return new TimedCountObservationResource($form->save($timedCountObservation));
    }

    /**
     * Delete timed count observation.
     *
     * @param  \App\TimedCountObservation  $timedCountObservation
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(TimedCountObservation $timedCountObservation)
    {
        $timedCountObservation->delete();

        return response()->json(null, 204);
    }
}
