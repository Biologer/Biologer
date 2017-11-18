<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FieldObservation as FieldObservationResource;

class PendingObservationsController extends Controller
{
    public function index(Request $request)
    {
        $query = FieldObservation::pending()->filter($request);

        if ($request->input('all', false)) {
            return FieldObservationResource::collection($query->get());
        }

        return FieldObservationResource::collection(
            $query->paginate($request->input('per_page', 15))
        );
    }
}
