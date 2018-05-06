<?php

namespace App\Http\Resources;

use App\Taxon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaxonCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'last_updated_at' => Taxon::latest('updated_at')->first()->updated_at->timestamp,
            ],
        ];
    }
}
