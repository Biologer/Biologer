<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SavePublication;
use App\Http\Resources\PublicationResource;
use App\Models\LiteratureObservation;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function destroy(Publication $publication)
    {
        return DB::transaction(function () use ($publication) {
            LiteratureObservation::connectedToPublication($publication)->each(function ($observation) {
                $observation->delete();
            });

            $publication->delete();

            return response()->noContent();
        });
    }
}
