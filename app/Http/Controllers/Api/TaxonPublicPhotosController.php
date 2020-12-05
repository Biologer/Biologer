<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PublicPhotoResource;
use App\Taxon;

class TaxonPublicPhotosController
{
    public function index(Taxon $taxon)
    {
        return PublicPhotoResource::collection($taxon->publicPhotos());
    }
}
