<?php

namespace App\Http\Controllers\Api\My;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Http\Forms\NewFieldObservationForm;
use App\Http\Forms\FieldObservationUpdateForm;

class FieldObservationsController extends Controller
{
    /**
     * Get field observations made by the user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $fieldObservations = FieldObservation::createdBy(auth()->user())
            ->with('observation');

        if (request('all')) {
            return $fieldObservations->get();
        }

        return $fieldObservations->paginate(
            request('per_page', 15)
        );
    }
}
