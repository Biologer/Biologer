<?php

namespace App\Http\Controllers\Contributor;

use App\Comment;
use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Forms\NewFieldObservationForm;
use App\Http\Forms\FieldObservationUpdateForm;

class FieldObservationsController extends Controller
{
    /**
     * Display a list of observations.
     */
    public function index()
    {
        return view('contributor.field-observations.index');
    }

    /**
     * Show page to add new observation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contributor.field-observations.create');
    }

    /**
     * Show form to edit field observation.
     */
    public function edit(FieldObservation $fieldObservation)
    {
        return view('contributor.field-observations.edit', [
            'observation' => $fieldObservation->load('observation.taxon'),
        ]);
    }
}
