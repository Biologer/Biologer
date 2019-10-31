<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FieldObservations\CustomFieldObservationsExport;
use App\FieldObservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FieldObservationsController
{
    use AuthorizesRequests;

    /**
     * Display list of all field observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.field-observations.index', [
            'exportColumns' => CustomFieldObservationsExport::availableColumnData(),
        ]);
    }

    /**
     * Show field observation details.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return \Illuminate\View\View
     */
    public function show(FieldObservation $fieldObservation)
    {
        $this->authorize('view', $fieldObservation);

        return view('admin.field-observations.show', [
            'fieldObservation' => $fieldObservation->load([
                'observation.taxon', 'activity.causer',
            ]),
        ]);
    }

    /**
     * Display form to edit pending observations.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return \Illuminate\View\View
     */
    public function edit(FieldObservation $fieldObservation)
    {
        $this->authorize('update', $fieldObservation);

        return view('admin.field-observations.edit', [
            'fieldObservation' => $fieldObservation->load([
                'observation.taxon.curators', 'observation.taxon.stages',
                'observedBy', 'identifiedBy',
            ]),
            'stages' => \App\Stage::all(),
            'observationTypes' => \App\ObservationType::all(),
        ]);
    }
}
