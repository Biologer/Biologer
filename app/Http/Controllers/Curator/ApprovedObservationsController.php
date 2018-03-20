<?php

namespace App\Http\Controllers\Curator;

use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApprovedObservationsController extends Controller
{
    /**
     * Display list of curator's approved observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('curator.approved-observations.index');
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
        ])->approved()->findOrFail($approvedObservation);

        $this->authorize('update', $fieldObservation);

        return view('curator.approved-observations.edit', [
            'observation' => $fieldObservation,
            'stages' => \App\Stage::all(),
            'observationTypes' => \App\ObservationType::all(),
        ]);
    }
}
