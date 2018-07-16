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
        $user = $request->user();
        $data = $this->collection;

        if ($user->hasAnyRole(['admin', 'curator'])) {
            $data = $this->collection->map(function ($taxon) use ($user) {
                $taxon->can_edit = $user->can('update', $taxon);
                $taxon->can_delete = $user->can('delete', $taxon);

                return $taxon->makeVisible(['can_edit', 'can_delete'])
                    ->makeHidden(['curators', 'ancestors']);
            });
        }

        $latestUpdatedTaxon = Taxon::latest('updated_at')->first();

        return [
            'data' => $data,
            'meta' => [
                'last_updated_at' => $latestUpdatedTaxon ? $latestUpdatedTaxon->updated_at->timestamp : 0,
            ],
        ];
    }
}
