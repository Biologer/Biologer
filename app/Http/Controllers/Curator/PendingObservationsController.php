<?php

namespace App\Http\Controllers\Curator;

use App\Exports\FieldObservations\CuratorPendingFieldObservationsCustomExport;
use App\FieldObservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PendingObservationsController
{
    use AuthorizesRequests;

    /**
     * Display list of curator's pending observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('curator.pending-observations.index', [
            'exportColumns' => CuratorPendingFieldObservationsCustomExport::availableColumnData(),
        ]);
    }

    /**
     * Show pending field observation details.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return \Illuminate\View\View
     */
    public function show(FieldObservation $fieldObservation)
    {
        $this->authorize('view', $fieldObservation);

        return view('curator.pending-observations.show', [
            'fieldObservation' => $fieldObservation->load([
                'observation.taxon', 'activity.causer',
            ]),
        ]);
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
            'observation.taxon.stages', 'observedBy', 'identifiedBy',
        ]);

        $this->authorize('update', $fieldObservation);

        return view('curator.pending-observations.edit', [
            'fieldObservation' => $fieldObservation,
            'stages' => \App\Stage::all(),
            'observationTypes' => \App\ObservationType::all(),
        ]);
    }
}
