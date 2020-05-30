<?php

namespace App\Http\Controllers\Contributor;

use App\Exports\LiteratureObservations\CustomLiteratureObservationsExport;
use App\LiteratureObservation;

class PublicLiteratureObservationsController
{
    /**
     * Show list of public observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contributor.public-literature-observations.index', [
            'exportColumns' => CustomLiteratureObservationsExport::availableColumnData(),
        ]);
    }

    /**
     * Show field observation details.
     *
     * @param  \App\LiteratureObservation  $literatureObservation
     * @return \Illuminate\View\View
     */
    public function show(LiteratureObservation $literatureObservation)
    {
        return view('contributor.public-literature-observations.show', [
            'literatureObservation' => $literatureObservation->load([
                'observation.taxon', 'observation.stage', 'publication', 'citedPublication',
            ]),
        ]);
    }
}
