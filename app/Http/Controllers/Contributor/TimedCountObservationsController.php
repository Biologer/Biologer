<?php

namespace App\Http\Controllers\Contributor;

use App\Http\Requests\StoreTimedCountObservation;
use App\Http\Requests\UpdateTimedCountObservation;
use App\Http\Resources\TimedCountObservationResource;
use App\TimedCountObservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TimedCountObservationsController
{
    /**
     * Get timed count observations.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $result = TimedCountObservation::with([
             'fieldObservations', 'viewGroup',
        ])->filter($request)->orderBy('id')->paginate($request->get('per_page', 15));

        return TimedCountObservationResource::collection($result);
    }

    /**
     * Add new timed count observation.
     *
     * @param StoreTimedCountObservation $form
     * @return TimedCountObservationResource
     */
    public function store(StoreTimedCountObservation $form)
    {
        return new TimedCountObservationResource($form->store());
    }

    /**
     * Display the specified resource.
     *
     * @param TimedCountObservation $timedCountObservation
     * @param Request $request
     * @return TimedCountObservationResource
     */
    public function show(TimedCountObservation $timedCountObservation, Request $request)
    {
        abort_unless($timedCountObservation->isCreatedBy($request->user()), 403);

        return new TimedCountObservationResource($timedCountObservation);
    }

    /**
     * Update timed count observation.
     *
     * @param TimedCountObservation $timedCountObservation
     * @param UpdateTimedCountObservation $form
     * @return TimedCountObservationResource
     */
    public function update(TimedCountObservation $timedCountObservation, UpdateTimedCountObservation $form)
    {
        return new TimedCountObservationResource($form->save($timedCountObservation));
    }

    /**
     * Delete timed count observation.
     *
     * @param TimedCountObservation $timedCountObservation
     * @return JsonResponse
     */
    public function destroy(TimedCountObservation $timedCountObservation)
    {
        $timedCountObservation->delete();

        return response()->json(null, 204);
    }
}
