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

        list($sortField, $sortOrder) = explode('.', request('sort_by', 'id.desc'));

        if (request('all')) {
            return $fieldObservations->orderBy($sortField, $sortOrder)->orderBy('id')->get();
        }

        return $fieldObservations->orderBy($sortField, $sortOrder)->orderBy('id')->paginate(
            request('per_page', 15)
        );
    }
}
