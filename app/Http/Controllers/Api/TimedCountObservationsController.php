<?php

namespace App\Http\Controllers\Api;

use App\TimedCountObservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TimedCountObservationsController
{
    use AuthorizesRequests;

    /**
     * Display list of all timed count observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contributor.timed-count-observations.index');
    }

    /**
     * Show timed count observation details.
     *
     * @param  \App\TimedCountObservation  $timedCountObservation
     * @return \Illuminate\View\View
     */
    public function show(TimedCountObservation $timedCountObservation)
    {
        $this->authorize('view', $timedCountObservation);

        return view('api.timed-count-observations.show', [
            'TimedCountObservation' => $timedCountObservation->load([
                'activity.causer',
            ]),
        ]);
    }
}
