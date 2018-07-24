<?php

namespace App\Http\Controllers\Curator;

use App\FieldObservation;
use App\Http\Controllers\Controller;

class PendingObservationsController extends Controller
{
    /**
     * Display list of curator's pending observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('curator.pending-observations.index');
    }

    /**
     * Display form to edit pending observations.
     *
     * @param  \App\FieldObservation $fieldObservation
     * @return \Illuminate\View\View
     */
    public function edit(FieldObservation $fieldObservation)
    {
        abort_unless($fieldObservation->isPending(), 404);

        $fieldObservation->load([
            'observation.taxon.curators', 'observation.taxon.stages',
            'observedBy', 'identifiedBy',
        ]);

        $this->authorize('update', $fieldObservation);

        return view('curator.pending-observations.edit', [
            'observation' => $fieldObservation,
            'stages' => \App\Stage::all(),
            'observationTypes' => \App\ObservationType::all(),
        ]);
    }
}
