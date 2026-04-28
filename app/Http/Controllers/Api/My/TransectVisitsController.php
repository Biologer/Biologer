<?php

namespace App\Http\Controllers\Api\My;

use App\Http\Resources\TransectVisitResource;
use App\TransectVisit;
use Illuminate\Http\Request;

class TransectVisitsController
{
    /**
     * Get field observations made by the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\TransectVisitResource
     */
    public function index(Request $request)
    {
        $result = TransectVisit::createdBy($request->user())->with([
            'fieldObservations',
        ])->filter($request)->orderBy('id')->paginate($request->get('per_page', 15));

        return TransectVisitResource::collection($result);
    }
}
