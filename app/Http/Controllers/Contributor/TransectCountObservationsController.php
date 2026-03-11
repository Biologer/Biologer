<?php

namespace App\Http\Controllers\Contributor;

use App\TransectCountObservation;
use Illuminate\Http\Request;

class TransectCountObservationsController
{
    /**
     * Display a list of observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contributor.transect-count-observations.index');
    }

    /**
     * Show transect count observation details.
     *
     * @param \App\TransectCountObservation $transectCountObservation
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function show(TransectCountObservation $transectCountObservation, Request $request)
    {
        abort_unless($transectCountObservation->isCreatedBy($request->user()), 403);

        return view('contributor.transect-count-observations.show', [
            'transectCountObservation' => $transectCountObservation->load([
                'transectSections', 'activity.causer',
            ]),
        ]);
    }

    /**
     * Show form to edit transect count observation.
     *
     * @param \App\TransectCountObservation $transectCountObservation
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function edit(TransectCountObservation $transectCountObservation, Request $request)
    {
        abort_unless($transectCountObservation->isCreatedBy($request->user()), 403);

        return view('contributor.transect-count-observations.edit', [
            'transectCountObservation' => $transectCountObservation->load([]),
        ]);
    }
}
