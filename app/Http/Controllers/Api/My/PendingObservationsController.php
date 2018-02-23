<?php

namespace App\Http\Controllers\Api\My;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Http\Resources\FieldObservationResource;

class PendingObservationsController extends Controller
{
    /**
     * Get pending obervation the authenticated user needs to look at.
     *
     * @return \App\Http\Resources\FieldObservationResource
     */
    public function index()
    {
        $query = FieldObservation::with([
            'observation.taxon', 'photos', 'activity.causer',
        ])->pending()->curatedBy(auth()->user())->filter(request());

        if (request()->has('page')) {
            return FieldObservationResource::collection(
                $query->paginate(request('per_page', 15))
            );
        }

        return FieldObservationResource::collection($query->get());
    }
}
