<?php

namespace App\Http\Controllers\Api\My;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Http\Resources\FieldObservationResource;

class FieldObservationsController extends Controller
{
    /**
     * Get field observations made by the user.
     *
     * @return \App\Http\Resources\FieldObservationResource
     */
    public function index()
    {
        $query = FieldObservation::createdBy(auth()->user())->with([
            'observation.taxon', 'observation.photos', 'activity.causer',
            'observation.types.translations', 'observedBy', 'identifiedBy',
        ])->filter(request())->orderBy('id');

        if (request()->has('page')) {
            return FieldObservationResource::collection(
                $query->paginate(request('per_page', 15))
            );
        }

        return FieldObservationResource::collection($query->get());
    }
}
