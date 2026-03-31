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
     * @param string $order_by (Default: 'id')
     * @param string $direction (Default: 'desc')
     * @param int $per_page (Default: 15)
     * @param string $updated_after (UNIX timestamp in secounds) 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\FieldObservationResource
     */
    public function index(Request $request)
    {
        $query = FieldObservation::createdBy($request->user())
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
            $query->where('id', '<', $request->get('before_id'));
        }

        if ($request->has('after_id')) {
            $query->where('id', '>', $request->get('after_id'));
        }

        $result = $query->orderBy(
            $request->get('order_by', 'id'),
            $request->get('direction', 'desc')
        )
            ->paginate($request->get('per_page', 15));

        return FieldObservationResource::collection($result);
    }
}
