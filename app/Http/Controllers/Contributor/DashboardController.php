<?php

namespace App\Http\Controllers\Contributor;

use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $observationCount = FieldObservation::createdBy(auth()->user())->count();

        return view('contributor.index', [
            'observationCount' => $observationCount,
        ]);
    }
}
