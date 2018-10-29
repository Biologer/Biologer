<?php

namespace App\Http\Controllers\Curator;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Exports\FieldObservations\CuratorUnidentifiableFieldObservationsCustomExport;

class UnidentifiableObservationsController extends Controller
{
    /**
     * Display list of curator's approved observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('curator.unidentifiable-observations.index', [
            'exportColumns' => CuratorUnidentifiableFieldObservationsCustomExport::availableColumnData(),
        ]);
    }

    /**
     * Display form to edit pending observations.
     *
     * @param  int|string  $unidentifiableObservation
     * @return \Illuminate\View\View
     */
    public function edit($unidentifiableObservation)
    {
        $fieldObservation = FieldObservation::with([
            'observation.taxon.curators', 'observation.taxon.stages',
            'observedBy', 'identifiedBy',
        ])->unidentifiable()->findOrFail($unidentifiableObservation);

        $this->authorize('update', $fieldObservation);

        return view('curator.unidentifiable-observations.edit', [
            'fieldObservation' => $fieldObservation,
            'stages' => \App\Stage::all(),
            'observationTypes' => \App\ObservationType::all(),
        ]);
    }
}
