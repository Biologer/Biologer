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
            'observation.taxon', 'photos',
        ])->filter(request())->orderBy('id');

        if (request()->has('all')) {
            return FieldObservationResource::collection($query->get());
        }

        return FieldObservationResource::collection(
            $query->paginate(request('per_page', 15))
        );
    }
}
