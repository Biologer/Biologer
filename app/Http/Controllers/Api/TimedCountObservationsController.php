<?php

namespace App\Http\Controllers\Api;

use App\Models\FieldObservation;
use App\Http\Requests\StoreTimedCountObservation;
use App\Http\Requests\UpdateTimedCountObservation;
use App\Http\Resources\FieldObservationResource;
use App\Http\Resources\TimedCountObservationResource;
use App\Models\TimedCountObservation;
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
            'fieldObservations', 'viewGroup', 'activity.causer',
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

    /**
     * Get field observations for a specific timed count observation.
     *
     * @param Request $request
     * @param TimedCountObservation $timedCountObservation
     * @return AnonymousResourceCollection
     */
    public function fieldObservations(Request $request, TimedCountObservation $timedCountObservation)
    {
        $result = FieldObservation::with([
            'observation.taxon', 'observation.photos', 'activity.causer',
            'observation.types.translations', 'observedBy', 'identifiedBy',
        ])
            ->filter($request)
            ->isTimedCount($timedCountObservation)
            ->orderBy('id')
            ->paginate($request->get('per_page', 15));

        return FieldObservationResource::collection($result);
    }
}
