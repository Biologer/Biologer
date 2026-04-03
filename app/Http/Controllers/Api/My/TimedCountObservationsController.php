<?php

namespace App\Http\Controllers\Api\My;

use App\Http\Resources\TimedCountObservationResource;
use App\TimedCountObservation;
use Illuminate\Http\Request;

class TimedCountObservationsController
{
    /**
     * Get timed count and all its observations made by the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Helpers\UpdatedAfter  $updatedAfter
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = TimedCountObservation::createdBy($request->user())
            ->with([
                'activity',
                'fieldObservations',
            ])
            ->filter($request);

        if ($request->has('before_id')) {
            $query->where('timed_count_observations.id', '<', $request->get('before_id'));
        }

        if ($request->has('after_id')) {
            $query->where('timed_count_observations.id', '>', $request->get('after_id'));
        }

        $result = $query->orderBy(
            $request->get('order_by', 'timed_count_observations.id'),
            $request->get('direction', 'desc')
        )->paginate($request->get('per_page', 15));

        return TimedCountObservationResource::collection($result);
    }
}
