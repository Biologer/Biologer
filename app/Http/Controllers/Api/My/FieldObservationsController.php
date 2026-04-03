<?php

namespace App\Http\Controllers\Api\My;

use App\FieldObservation;
use App\Http\Resources\FieldObservationResource;
use Illuminate\Http\Request;

class FieldObservationsController
{
    /**
     * Get field observations made by the user.
     *
     * Available query parameters:
     * @param Request $request
     * @return FieldObservationResource
     */
    public function index(Request $request)
    {
        $result = FieldObservation::createdBy($request->user())
            ->isFieldObservation()
            ->with([
                'observation.taxon',
                'observation.photos',
                'activity.causer',
                'observation.types.translations',
                'observedBy',
                'identifiedBy',
            ])
            ->filter($request);

        if ($request->has('before_id')) {
            $result->where('id', '<', $request->query('before_id'));
        }

        if ($request->has('after_id')) {
            $result->where('id', '>', $request->query('after_id'));
        }

        $result->orderBy('id');

        return FieldObservationResource::collection($result);
    }
}
