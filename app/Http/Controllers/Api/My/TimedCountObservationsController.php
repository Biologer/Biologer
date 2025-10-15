<?php

namespace App\Http\Controllers\Api\My;

use App\Http\Resources\TimedCountObservationResource;
use App\TimedCountObservation;
use Illuminate\Http\Request;

class TimedCountObservationsController
{
    /**
     * Get field observations made by the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\TimedCountObservationResource
     */
    public function index(Request $request)
    {
        $result = TimedCountObservation::createdBy($request->user())->with([
            'fieldObservations',
        ])->filter($request)->orderBy('id')->paginate($request->get('per_page', 15));

        return TimedCountObservationResource::collection($result);
    }
}
