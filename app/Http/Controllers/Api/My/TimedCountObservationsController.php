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

        if ($request->has('before_id') && $request->has('after_id')) {
            abort(422, 'Cannot use both before_id and after_id.');
        }

        if ($request->has('before_id')) {
            $result->where('timed_count_observations.id', '<', $request->query('before_id'));
        }

        if ($request->has('after_id')) {
            $result->where('timed_count_observations.id', '>', $request->query('after_id'));
        }

        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';

        $result->orderBy('id', $direction);

        return TimedCountObservationResource::collection(
            $result->paginate(min($request->input('per_page', 15), 250))
        );
    }
}
