<?php

namespace App\Http\Controllers\Curator;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Exports\FieldObservations\CuratorApprovedFieldObservationsCustomExport;

class ApprovedObservationsController extends Controller
{
    /**
     * Display list of curator's approved observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('curator.approved-observations.index', [
            'exportColumns' => CuratorApprovedFieldObservationsCustomExport::availableColumnData(),
        ]);
    }

    /**
     * Display form to edit pending observations.
     *
     * @param  int|string  $approvedObservation
     * @return \Illuminate\View\View
     */
    public function edit($approvedObservation)
    {
        $fieldObservation = FieldObservation::with([
            'observation.taxon.curators', 'observation.taxon.stages',
            'observedBy', 'identifiedBy',
        ])->approved()->findOrFail($approvedObservation);

        $this->authorize('update', $fieldObservation);

        return view('curator.approved-observations.edit', [
            'fieldObservation' => $fieldObservation,
            'stages' => \App\Stage::all(),
            'observationTypes' => \App\ObservationType::all(),
        ]);
    }
}
