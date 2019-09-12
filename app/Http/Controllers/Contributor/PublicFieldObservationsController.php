<?php

namespace App\Http\Controllers\Contributor;

use App\Exports\FieldObservations\CustomFieldObservationsExport;

use App\FieldObservation;
use App\Http\Controllers\Controller;

class PublicFieldObservationsController extends Controller
{
    /**
     * Show list of public observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contributor.public-field-observations.index', [
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
        return view('contributor.public-field-observations.show', [
            'fieldObservation' => $fieldObservation->load([
                'observation.taxon',
            ]),
        ]);
    }
}
