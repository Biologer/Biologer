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

        $allowedOrderBy = ['id', 'created_at'];
        $orderBy = in_array($request->query('order_by'), $allowedOrderBy)
            ? $request->query('order_by')
            : 'id';

        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $result->orderBy($orderBy, $direction)
            ->orderBy('id', $direction);

        $perPage = min($request->integer('per_page', 15), 100);

        $result->filter($request);

        return FieldObservationResource::collection(
            $result->paginate($perPage)
        );
    }
}
