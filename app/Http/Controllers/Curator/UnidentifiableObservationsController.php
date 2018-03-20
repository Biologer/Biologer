<?php

namespace App\Http\Controllers\Curator;

use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnidentifiableObservationsController extends Controller
{
    /**
     * Display list of curator's approved observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('curator.unidentifiable-observations.index');
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
        ])->unidentifiable()->findOrFail($unidentifiableObservation);

        $this->authorize('update', $fieldObservation);

        return view('curator.unidentifiable-observations.edit', [
            'observation' => $fieldObservation,
            'stages' => \App\Stage::all(),
            'observationTypes' => \App\ObservationType::all(),
        ]);
    }
}
