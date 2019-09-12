<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SavePublication;
use App\Http\Resources\PublicationResource;
use App\Publication;

class PublicationsController extends Controller
{
    public function index()
    {
        return PublicationResource::collection(Publication::paginate());
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
