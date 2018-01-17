<?php

namespace App\Http\Controllers\Api\My;

use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Forms\NewFieldObservationForm;
use App\Http\Forms\FieldObservationUpdateForm;
use App\Http\Resources\FieldObservation as FieldObservationResource;

class FieldObservationsController extends Controller
{
    /**
     * Get field observations made by the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = FieldObservation::createdBy(auth()->user())->with([
            'observation.taxon', 'photos',
        ])->filter($request)->orderBy('id');

        if ($request->has('all')) {
            return FieldObservationResource::collection($query->get());
        }

        return FieldObservationResource::collection(
            $query->paginate($request->input('per_page', 15))
        );
    }
}
