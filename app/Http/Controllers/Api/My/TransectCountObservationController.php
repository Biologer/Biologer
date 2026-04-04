<?php

namespace App\Http\Controllers\Api\My;

use App\Http\Resources\TransectCountObservationResource;
use App\TransectCountObservation;
use Illuminate\Http\Request;

class TransectCountObservationController
{
    /**
     * Get transect visits made by the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\TransectCountObservationResource
     */
    public function index(Request $request)
    {
        $result = TransectCountObservation::createdBy($request->user())->with([
            'transectSections',
        ])->filter($request)->orderBy('id')->paginate($request->get('per_page', 15));

        return TransectCountObservationResource::collection($result);
    }
}
