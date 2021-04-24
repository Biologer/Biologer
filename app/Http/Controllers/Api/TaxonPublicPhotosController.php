<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use App\Http\Resources\PublicPhotoResource;
use App\Photo;
use App\Taxon;
use Illuminate\Http\Request;

class TaxonPublicPhotosController
{
    public function index(Taxon $taxon, Request $request)
    {
        $photos = Photo::public()->whereHas('observations', function ($query) use ($taxon, $request) {
            $query->approved()->ofTaxa($taxon->selfAndDescendantsIds());

            if ($request->boolean('excludeDead')) {
                $query->where(function ($query) {
                    $query
                        ->where('details_type', '!=', (new FieldObservation)->getMorphClass())
                        ->orWhereHasMorph('details', [FieldObservation::class], function ($query) {
                            $query->where('found_dead', false);
                        });
                });
            }
        })->with('observations.details', 'observations.stage')->get()->filter->public_url;

        return PublicPhotoResource::collection($photos);
    }
}
