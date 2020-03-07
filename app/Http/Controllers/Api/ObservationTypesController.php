<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ObservationTypeResource;
use App\ObservationType;

class ObservationTypesController
{
    public function index()
    {
        return ObservationTypeResource::collection(ObservationType::all());
    }
}
