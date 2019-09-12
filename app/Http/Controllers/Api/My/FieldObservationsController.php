<?php

namespace App\Http\Controllers\Api\My;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Http\Resources\FieldObservationResource;
use Illuminate\Http\Request;

class FieldObservationsController extends Controller
{
    /**
     * Get field observations made by the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\FieldObservationResource
     */
    public function index(Request $request)
    {
        $result = FieldObservation::createdBy($request->user())->with([
            'observation.taxon', 'observation.photos', 'activity.causer',
            'observation.types.translations', 'observedBy', 'identifiedBy',
        ])->filter($request)->orderBy('id')->paginate($request->get('per_page', 15));

        return FieldObservationResource::collection($result);
    }
}
