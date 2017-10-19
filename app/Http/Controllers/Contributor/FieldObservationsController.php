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
        $observations = FieldObservation::paginate();

        return view('contributor.field-observations.index', [
            'observations' => $observations,
        ]);
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
     * Add new field observation.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(NewFieldObservationForm $form)
    {
        $observation = $form->save();

        if (request()->ajax()) {
            return response()->json($observation, 201);
        }

        return redirect()->route('contributor.field-observations.index');
    }

    /**
     * Show form to edit field observation.
     */
    public function edit($id)
    {
        $observation = FieldObservation::findOrFail($id);

        return view('contributor.field-observations.edit', [
            'observation' => $observation,
        ]);
    }

    public function update($id, FieldObservationUpdateForm $form)
    {
        $observation = FieldObservation::findOrFail($id);

        $form->save($observation);

        if (request()->ajax()) {
            return response()->json($observation, 200);
        }

        return redirect()->route('contributor.field-observations.index');
    }
}
