<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SavePublication;
use App\Http\Resources\PublicationResource;
use App\Publication;
use Illuminate\Http\Request;

class PublicationsController
{
    public function index(Request $request)
    {
        return PublicationResource::collection(Publication::filter($request)->paginate($request->per_page ?? 15));
    }

    public function store(SavePublication $request)
    {
        return new PublicationResource($request->save(new Publication()));
    }

    public function update(SavePublication $request, Publication $publication)
    {
        return new PublicationResource($request->save($publication));
    }
}
