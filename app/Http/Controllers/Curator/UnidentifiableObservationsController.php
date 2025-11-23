<?php

namespace App\Http\Controllers\Curator;

use App\Exports\FieldObservations\CuratorUnidentifiableFieldObservationsCustomExport;
use App\Models\FieldObservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UnidentifiableObservationsController
{
    use AuthorizesRequests;

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
     * Show unidentifiable field observation details.
     *
     * @param  \App\Models\FieldObservation  $fieldObservation
     * @return \Illuminate\View\View
     */
    public function show(FieldObservation $fieldObservation)
    {
        $this->authorize('view', $fieldObservation);

        return view('curator.unidentifiable-observations.show', [
            'fieldObservation' => $fieldObservation->load([
                'observation.taxon', 'activity.causer',
            ]),
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
            'stages' => \App\Models\Stage::all(),
            'observationTypes' => \App\Models\ObservationType::all(),
        ]);
    }
}
