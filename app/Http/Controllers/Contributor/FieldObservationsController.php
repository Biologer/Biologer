<?php

namespace App\Http\Controllers\Contributor;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Exports\ContributorFieldObservationsExport;

class FieldObservationsController extends Controller
{
    /**
     * Display a list of observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contributor.field-observations.index', [
            'exportColumns' => ContributorFieldObservationsExport::availableColumnData(),
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
     * @return \Illuminate\View\View
     */
    public function edit(FieldObservation $fieldObservation)
    {
        return view('contributor.field-observations.edit', [
            'fieldObservation' => $fieldObservation->load([
                'observation.taxon.stages', 'observedBy', 'identifiedBy',
            ]),
        ]);
    }
}
