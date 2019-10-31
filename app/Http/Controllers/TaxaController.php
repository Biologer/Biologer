<?php

namespace App\Http\Controllers;

use App\Taxon;

class TaxaController
{
    /**
     * Display taxon details.
     *
     * @param  \App\Taxon  $taxon
     * @return \Illuminate\View\View
     */
    public function show(Taxon $taxon)
    {
        $photos = collect();

        if ($taxon->isGenusOrLower()) {
            $photos = $taxon->publicPhotos()
                ->filter->public_url
                ->values()
                ->map->forGallery();
        }

        return view('taxa.show', [
            'taxon' => $taxon,
            'photos' => $photos,
            'descendants' => $taxon->isGenusOrLower() ? $taxon->lowerRankDescendants() : collect(),
        ]);
    }
}
