<?php

namespace App\Http\Controllers\Admin;

use App\FieldObservation;
use App\Http\Resources\FieldObservationResource;
use App\TimedCountObservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TimedCountObservationsController
{
    use AuthorizesRequests;

    /**
     * Display list of all field observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.timed-count-observations.index');
    }

    /**
     * Show field observation details.
     *
     * @param  \App\TimedCountObservation  $timedCountObservation
     * @return \Illuminate\View\View
     */
    public function show(TimedCountObservation $timedCountObservation)
    {
        $this->authorize('view', $timedCountObservation);

        return view('admin.timed-count-observations.show', [
            'timedCountObservation' => $timedCountObservation->load([
                'fieldObservations', 'activity.causer',
            ]),
        ]);
    }

    /**
     * Get field observations for a specific timed count observation.
     *
     * @param Request $request
     * @param TimedCountObservation $timedCountObservation
     * @return AnonymousResourceCollection
     */
    public function fieldObservations(Request $request, TimedCountObservation $timedCountObservation)
    {
        $result = FieldObservation::with([
            'observation.taxon', 'observation.photos', 'activity.causer',
            'observation.types.translations', 'observedBy', 'identifiedBy',
        ])
            ->filter($request)
            ->isTimedCount($timedCountObservation)
            ->orderBy('id')
            ->paginate($request->get('per_page', 15));

        return FieldObservationResource::collection($result);
    }

}
