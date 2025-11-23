<?php

namespace App\Http\Controllers\Contributor;

use App\Models\FieldObservation;

class DashboardController
{
    /**
     * Show dashboard.
     *
     * @return \Illuminate\View\View
     */
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
