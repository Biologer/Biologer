<?php

namespace App\Http\Controllers\Contributor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $observationCount = auth()->user()->fieldObservations()->count();

        return view('contributor.index', [
            'observationCount' => $observationCount,
        ]);
    }
}
