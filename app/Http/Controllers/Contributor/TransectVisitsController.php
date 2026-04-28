<?php

namespace App\Http\Controllers\Contributor;

use App\TransectVisit;
use Illuminate\Http\Request;

class TransectVisitsController
{
    /**
     * Display a list of visits.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contributor.transect-visits.index');
    }

    /**
     * Show transect visit details.
     *
     * @param \App\TransectVisit $transectVisit
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function show(TransectVisit $transectVisit, Request $request)
    {
        abort_unless($transectVisit->isCreatedBy($request->user()), 403);

        return view('contributor.transect-visits.show', [
            'transectVisit' => $transectVisit->load([
                'fieldObsevations', 'activity.causer',
            ]),
        ]);
    }

    /**
     * Show form to edit transect visit.
     *
     * @param \App\TransectVisit $transectVisit
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function edit(TransectVisit $transectVisit, Request $request)
    {
        abort_unless($transectVisit->isCreatedBy($request->user()), 403);

        return view('contributor.transect-visits.edit', [
            'transectVisit' => $transectVisit->load(),
        ]);
    }
}
