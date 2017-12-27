<?php

namespace App\Http\Controllers\Curator;

use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PendingObservationsController extends Controller
{
    public function index()
    {
        return view('curator.pending-observations.index');
    }

    public function edit($id)
    {
        $fieldObservation = FieldObservation::pending()->findOrFail($id);

        $this->authorize('update', $fieldObservation);

        return view('curator.pending-observations.edit', [
            'observation' => $fieldObservation,
        ]);
    }
}
