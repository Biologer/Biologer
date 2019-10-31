<?php

namespace App\Http\Controllers\Curator;

use App\Exports\FieldObservations\CuratorApprovedFieldObservationsCustomExport;
use App\FieldObservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApprovedObservationsController
{
    use AuthorizesRequests;

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
     * Show approved field observation details.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return \Illuminate\View\View
     */
    public function show(FieldObservation $fieldObservation)
    {
        $this->authorize('view', $fieldObservation);

        return view('curator.approved-observations.show', [
            'fieldObservation' => $fieldObservation->load([
                'observation.taxon', 'activity.causer',
            ]),
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
