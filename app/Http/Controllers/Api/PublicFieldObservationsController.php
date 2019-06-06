<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PublicFieldObservationResource;

class PublicFieldObservationsController extends Controller
{
    /**
     * Get field observations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $result = FieldObservation::with([
             'observation.taxon', 'observation.photos', 'activity.causer',
             'observation.types.translations', 'observedBy', 'identifiedBy',
        ])->public()->filter($request)->orderBy('id')->paginate($request->get('per_page', 15));

        return PublicFieldObservationResource::collection($result);
    }
}
