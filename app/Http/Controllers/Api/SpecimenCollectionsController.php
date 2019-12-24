<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SaveSpecimenCollection;
use App\Http\Resources\SpecimenCollectionResource;
use App\SpecimenCollection;
use Illuminate\Http\Request;

class SpecimenCollectionsController
{
    public function index(Request $request)
    {
        return SpecimenCollectionResource::collection(SpecimenCollection::filter($request)->paginate($request->per_page ?? 15));
    }

    public function store(SaveSpecimenCollection $request)
    {
        return new SpecimenCollectionResource($request->save(new SpecimenCollection()));
    }

    public function update(SaveSpecimenCollection $request, SpecimenCollection $specimenCollection)
    {
        return new SpecimenCollectionResource($request->save($specimenCollection));
    }
}
