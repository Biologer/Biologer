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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
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
            $result->where('id', '<', $request->input('before_id'));
        }

        if ($request->has('after_id')) {
            $result->where('id', '>', $request->input('after_id'));
        }

        $result->orderBy('id');

        return FieldObservationResource::collection(
            $result->paginate(min($request->input('per_page', 15), 250))
        );
    }
}
