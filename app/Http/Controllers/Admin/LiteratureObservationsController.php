<?php

namespace App\Http\Controllers\Admin;

use App\LiteratureObservation;
use App\Http\Controllers\Controller;
use App\Exports\LiteratureObservations\CustomLiteratureObservationsExport;

class LiteratureObservationsController extends Controller
{
    public function index()
    {
        return view('admin.literature-observations.index', [
            'exportColumns' => CustomLiteratureObservationsExport::availableColumnData(),
        ]);
    }

    public function show(LiteratureObservation $literatureObservation)
    {
        return view('admin.literature-observations.show', [
            'literatureObservation' => $literatureObservation->load([
                'observation.taxon', 'observation.stage', 'publication', 'citedPublication',
            ]),
        ]);
    }

    public function create()
    {
        return view('admin.literature-observations.create');
    }

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
