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

    /**
     * Return descendants curators.
     *
     * @param Taxon $taxon
     * @return \Illuminate\Http\JsonResponse
     */
    public function descendantsCurators(Taxon $taxon)
    {
        $descendants = $taxon->descendants()->with('curators')->get();

        $taxa = $descendants->map(function ($t) {
            return [
                'id' => $t->id,
                'name' => $t->name,
                'curators' => $t->curators->map(function ($u) {
                    return [
                        'id' => $u->id,
                        'name' => $u->full_name,
                    ];
                }),
            ];
        });

        return response()->json([
            'taxa' => $taxa,
        ]);
    }
}
