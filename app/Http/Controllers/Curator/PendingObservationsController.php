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
     * @param  int|string  $id
     * @return \Illuminate\View\View
     */
    public function edit($pendingObservation)
    {
        $fieldObservation = FieldObservation::with([
            'observation.taxon.curators', 'observation.taxon.stages',
        ])->pending()->findOrFail($pendingObservation);

        $this->authorize('update', $fieldObservation);

        return view('curator.pending-observations.edit', [
            'observation' => $fieldObservation,
            'stages' => \App\Stage::all(),
        ]);
    }
}
