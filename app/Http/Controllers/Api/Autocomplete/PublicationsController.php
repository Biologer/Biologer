<?php

namespace App\Http\Controllers\Api\Autocomplete;

use App\Http\Resources\PublicationResource;
use App\Publication;
use Illuminate\Http\Request;

class PublicationsController
{
    public function index(Request $request)
    {
        $publications = Publication::filter($request, [
            'citation' => \App\Filters\AttributeLike::class,
        ])->orderBy('citation')->paginate(10);

        return PublicationResource::collection($publications);
    }
}
