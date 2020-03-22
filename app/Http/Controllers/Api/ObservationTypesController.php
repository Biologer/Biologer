<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ObservationTypeResource;
use App\ObservationType;
use Illuminate\Http\Request;

class ObservationTypesController
{
    public function index(Request $request)
    {
        return ObservationTypeResource::collection(ObservationType::filter($request)->get());
    }
}
