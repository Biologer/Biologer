<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreLiteratureObservation;
use App\Http\Requests\UpdateLiteratureObservation;
use App\Http\Resources\LiteratureObservationResource;
use App\LiteratureObservation;
use Illuminate\Http\Request;

class LiteratureObservationsController
{
    /**
     * Get literature observations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $observations = LiteratureObservation::filter($request)->with([
             'observation.taxon',
        ])->when($request->user()->hasRole('admin'), function ($query) {
            $query->with('activity.causer');
        })->orderBy('id')->paginate($request->input('per_page', 15));

        return LiteratureObservationResource::collection($observations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LiteratureObservation  $literatureObservation
     * @return \App\Http\Resources\LiteratureObservationResource
     */
    public function show(LiteratureObservation $literatureObservation)
    {
        return new LiteratureObservationResource($literatureObservation);
    }

    /**
     * Store literature observation.
     *
     * @param  \App\Http\Requests\StoreLiteratureObservation  $request
     * @return \App\Http\Resources\LiteratureObservationResource
     */
    public function store(StoreLiteratureObservation $request)
    {
        return new LiteratureObservationResource($request->save());
    }

    /**
     * Update literature observation.
     *
     * @param  \App\LiteratureObservation  $literatureObservation
     * @param  \App\Http\Requests\UpdateLiteratureObservation  $request
     * @return \App\Http\Resources\LiteratureObservationResource
     */
    public function update(LiteratureObservation $literatureObservation, UpdateLiteratureObservation $request)
    {
        return new LiteratureObservationResource($request->save($literatureObservation));
    }

    /**
     * Delete literature observation.
     *
     * @param  \App\LiteratureObservation  $literatureObservation
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(LiteratureObservation $literatureObservation)
    {
        $literatureObservation->delete();

        return response()->json(null, 204);
    }
}
