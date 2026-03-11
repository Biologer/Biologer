<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreTransectCountObservation;
use App\Http\Requests\UpdateTransectCountObservation;
use App\Http\Resources\TransectCountObservationResource;
use App\TransectCountObservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransectCountObservationsController
{
    /**
     * Get transect count observations.
     *
     * @param  Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $result = TransectCountObservation::with([
            'transectSections', 'activity.causer',
        ])->filter($request)->orderBy('id')->paginate($request->get('per_page', 15));

        return TransectCountObservationResource::collection($result);
    }

    /**
     * Add new timed count observation.
     *
     * @param  StoreTransectCountObservation $form
     * @return TransectCountObservationResource
     */
    public function store(StoreTransectCountObservation $form)
    {
        return new TransectCountObservationResource($form->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  TransectCountObservation $transectCountObservation
     * @param  Request $request
     * @return TransectCountObservationResource
     */
    public function show(TransectCountObservation $transectCountObservation, Request $request)
    {
        abort_unless($transectCountObservation->isCreatedBy($request->user()), 403);

        return new TransectCountObservationResource($transectCountObservation);
    }

    /**
     * Update timed count observation.
     *
     * @param  TransectCountObservation $transectCountObservation
     * @param  UpdateTransectCountObservation $form
     * @return TransectCountObservationResource
     */
    public function update(TransectCountObservation $transectCountObservation, UpdateTransectCountObservation $form)
    {
        return new TransectCountObservationResource($form->save($transectCountObservation));
    }

    /**
     * Delete timed count observation.
     *
     * @param  TransectCountObservation $transectCountObservation
     * @return JsonResponse
     */
    public function destroy(TransectCountObservation $transectCountObservation)
    {
        $transectCountObservation->delete();

        return response()->json(null, 204);
    }
}
