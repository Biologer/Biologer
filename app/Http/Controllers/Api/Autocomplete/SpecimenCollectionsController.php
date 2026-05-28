<?php

namespace App\Http\Controllers\Api\Autocomplete;

use App\Http\Resources\SpecimenCollectionResource;
use App\SpecimenCollection;
use Illuminate\Http\Request;

class SpecimenCollectionsController
{
    public function index(Request $request)
    {
        $collections = SpecimenCollection::filter($request, [
            'name' => \App\Filters\AttributeLike::class,
        ])->orderBy('name')->paginate(10);

        return SpecimenCollectionResource::collection($collections);
    }
}
