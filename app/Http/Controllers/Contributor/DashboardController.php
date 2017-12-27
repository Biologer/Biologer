<?php

namespace App\Http\Controllers\Contributor;

use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('contributor.index', [
            'observationCount' => FieldObservation::createdBy($user)->count(),
            'pendingObservationCount' => FieldObservation::unapproved()->createdBy($user)->count(),
            'approvedObservationCount' => FieldObservation::approved()->createdBy($user)->count(),
        ]);
    }
}
