<?php

namespace App\Http\Controllers\Api\My;

use App\Http\Resources\TransectSectionResource;
use App\TransectSection;
use Illuminate\Http\Request;

class TransectSectionController
{
    /**
     * Get transect visits made by the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\TransectSectionResource
     */
    public function index(Request $request)
    {
        $result = TransectSection::createdBy($request->user())->with([
            'transectVisits',
        ])->filter($request)->orderBy('id')->paginate($request->get('per_page', 15));

        return TransectSectionResource::collection($result);
    }
}
