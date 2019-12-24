<?php

namespace App\Http\Controllers\Api;

use App\CollectionObservation;
use App\Http\Requests\StoreCollectionObservation;
use App\Http\Requests\UpdateCollectionObservation;
use App\Http\Resources\CollectionObservationResource;
use Illuminate\Http\Request;

class CollectionObservationsController
{
    /**
     * Get collection observations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */

    /**
     * Get collection observations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $observations = CollectionObservation::filter($request)->with([
            'observation.taxon',
        ])->when($request->user()->hasRole('admin'), function ($query) {
            $query->with('activity.causer');
        })->orderBy('id')->paginate($request->input('per_page', 15));

        return CollectionObservationResource::collection($observations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CollectionObservation  $collectionObservation
     * @return \App\Http\Resources\CollectionObservationResource
     */
    public function show(CollectionObservation $collectionObservation)
    {
        return new CollectionObservationResource($collectionObservation);
    }

    /**
     * Store collection observation.
     *
     * @param  \App\Http\Requests\StoreCollectionObservation  $request
     * @return \App\Http\Resources\CollectionObservationResource
     */
    public function store(StoreCollectionObservation $request)
    {
        return new CollectionObservationResource($request->save());
    }

    /**
     * Update collection observation.
     *
     * @param  \App\CollectionObservation  $collectionObservation
     * @param  \App\Http\Requests\UpdateCollectionObservation  $request
     * @return \App\Http\Resources\CollectionObservationResource
     */
    public function update(UpdateCollectionObservation $request, CollectionObservation $collectionObservation)
    {
        return new CollectionObservationResource($request->save($collectionObservation));
    }

    /**
     * Delete collection observation.
     *
     * @param  \App\CollectionObservation  $collectionObservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(CollectionObservation $collectionObservation)
    {
        $collectionObservation->delete();

        return response()->noContent();
    }
}
