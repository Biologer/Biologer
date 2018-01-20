<?php

namespace App\Http\Controllers\Contributor;

use App\FieldObservation;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('contributor.index', [
            'observationCount' => FieldObservation::createdBy($user)->count(),
            'pendingObservationCount' => FieldObservation::pending()->createdBy($user)->count(),
            'approvedObservationCount' => FieldObservation::approved()->createdBy($user)->count(),
            'unidentifiableObservationCount' => FieldObservation::unidentifiable()->createdBy($user)->count(),
        ]);
    }
}
