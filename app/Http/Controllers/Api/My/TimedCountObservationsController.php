<?php

namespace App\Http\Controllers\Api\My;

use App\Http\Resources\TimedCountObservationResource;
use App\TimedCountObservation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TimedCountObservationsController
{
    /**
     * Get timed count and all its observations made by the user.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $result = TimedCountObservation::createdBy($request->user())
            ->with([
                'fieldObservations',
            ])
            ->filter($request);

        if ($request->has('before_id')) {
            $result->where('timed_count_observations.id', '<', $request->query('before_id'));
        }

        if ($request->has('after_id')) {
            $result->where('timed_count_observations.id', '>', $request->query('after_id'));
        }

        $result = $result->orderBy(
            $request->query('order_by', 'timed_count_observations.id'),
            $request->query('direction', 'desc')
        )->paginate($request->query('per_page', 15));

        return TimedCountObservationResource::collection($result);
    }
}
