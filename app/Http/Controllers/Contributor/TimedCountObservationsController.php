<?php

namespace App\Http\Controllers\Contributor;

use App\TimedCountObservation;
use Illuminate\Http\Request;

class TimedCountObservationsController
{
    /**
     * Display a list of observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contributor.timed-count-observations.index');
    }

    /**
     * Show field observation details.
     *
     * @param \App\TimedCountObservation $timedCountObservation
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function show(TimedCountObservation $timedCountObservation, Request $request)
    {
        abort_unless($timedCountObservation->isCreatedBy($request->user()), 403);

        return view('contributor.timed-count-observations.show', [
            'timedCountObservation' => $timedCountObservation->load([
                'fieldObservations', 'activity.causer',
            ]),
        ]);
    }

    /**
     * Show form to edit timed count observation.
     *
     * @param \App\TimedCountObservation $timedCountObservation
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function edit(TimedCountObservation $timedCountObservation, Request $request)
    {
        abort_unless($timedCountObservation->isCreatedBy($request->user()), 403);

        return view('contributor.field-observations.edit', [
            'timedCountObservation' => $timedCountObservation->load([
               'observedBy',
            ]),
        ]);
    }
}
