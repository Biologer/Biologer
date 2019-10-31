<?php

namespace App\Http\Controllers\Contributor;

use App\Exports\FieldObservations\ContributorFieldObservationsCustomExport;
use App\FieldObservation;
use Illuminate\Http\Request;

class FieldObservationsController
{
    /**
     * Display a list of observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contributor.field-observations.index', [
            'exportColumns' => ContributorFieldObservationsCustomExport::availableColumnData(),
        ]);
    }

    /**
     * Show field observation details.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(FieldObservation $fieldObservation, Request $request)
    {
        abort_unless($fieldObservation->isCreatedBy($request->user()), 403);

        return view('contributor.field-observations.show', [
            'fieldObservation' => $fieldObservation->load([
                'observation.taxon', 'activity.causer',
            ]),
        ]);
    }

    /**
     * Show page to add new observation.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('contributor.field-observations.create');
    }

    /**
     * Show form to edit field observation.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(FieldObservation $fieldObservation, Request $request)
    {
        abort_unless($fieldObservation->isCreatedBy($request->user()), 403);

        return view('contributor.field-observations.edit', [
            'fieldObservation' => $fieldObservation->load([
                'observation.taxon.stages', 'observedBy', 'identifiedBy',
            ]),
        ]);
    }
}
