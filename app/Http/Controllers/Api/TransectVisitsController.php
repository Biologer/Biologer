<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use App\Http\Requests\StoreTransectVisit;
use App\Http\Requests\UpdateTransectVisit;
use App\Http\Resources\FieldObservationResource;
use App\Http\Resources\TransectVisitResource;
use App\TransectVisit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransectVisitsController
{
    /**
     * Get transect visits.
     *
     * @param  Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $result = TransectVisit::with([
            'fieldObservations', 'viewGroup', 'activity.causer',
        ])->filter($request)->orderBy('id')->paginate($request->get('per_page', 15));

        return TransectVisitResource::collection($result);
    }

    /**
     * Add new timed count observation.
     *
     * @param  StoreTransectVisit $form
     * @return TransectVisitResource
     */
    public function store(StoreTransectVisit $form)
    {
        return new TransectVisitResource($form->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  TransectVisit $transectVisist
     * @param  Request $request
     * @return TransectVisitResource
     */
    public function show(TransectVisit $transectVisist, Request $request)
    {
        abort_unless($transectVisist->isCreatedBy($request->user()), 403);

        return new TransectVisitResource($transectVisist);
    }

    /**
     * Update timed count observation.
     *
     * @param  TransectVisit $transectVisist
     * @param  UpdateTransectVisit $form
     * @return TransectVisitResource
     */
    public function update(TransectVisit $transectVisist, UpdateTransectVisit $form)
    {
        return new TransectVisitResource($form->save($transectVisist));
    }

    /**
     * Delete timed count observation.
     *
     * @param  TransectVisit $transectVisist
     * @return JsonResponse
     */
    public function destroy(TransectVisit $transectVisist)
    {
        $transectVisist->delete();

        return response()->json(null, 204);
    }

    /**
     * Get field observations for a specific transect visit.
     *
     * @param Request $request
     * @param TransectVisit $transectVisist
     * @return AnonymousResourceCollection
     */
    public function fieldObservations(Request $request, TransectVisit $transectVisist)
    {

        $result = FieldObservation::with([
            'observation.taxon', 'observation.photos', 'activity.causer',
            'observation.types.translations', 'observedBy', 'identifiedBy',
        ])
            ->filter($request)
            ->isTransectVisit($transectVisist)
            ->orderBy('id')
            ->paginate($request->get('per_page', 15));

        return FieldObservationResource::collection($result);
    }
}
