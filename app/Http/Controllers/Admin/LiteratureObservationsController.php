<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LiteratureObservations\CustomLiteratureObservationsExport;
use App\Models\LiteratureObservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LiteratureObservationsController
{
    use AuthorizesRequests;

    /**
     * Show page with list of literature observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.literature-observations.index', [
            'exportColumns' => CustomLiteratureObservationsExport::availableColumnData(),
        ]);
    }

    /**
     * Show literature observation details.
     *
     * @param  \App\Models\LiteratureObservation  $literatureObservation
     * @return \Illuminate\View\View
     */
    public function show(LiteratureObservation $literatureObservation)
    {
        return view('admin.literature-observations.show', [
            'literatureObservation' => $literatureObservation->load([
                'observation.taxon', 'observation.stage', 'publication', 'citedPublication',
                'activity.causer',
            ]),
        ]);
    }

    /**
     * Show form to create literature observation.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.literature-observations.create');
    }

    /**
     * Show literature observation edit form.
     *
     * @param  \App\Models\LiteratureObservation  $literatureObservation
     * @return \Illuminate\View\View
     */
    public function edit(LiteratureObservation $literatureObservation)
    {
        $this->authorize('update', $literatureObservation);

        return view('admin.literature-observations.edit', [
            'literatureObservation' => $literatureObservation->load([
                'observation.taxon.stages', 'publication', 'citedPublication',
            ]),
        ]);
    }
}
