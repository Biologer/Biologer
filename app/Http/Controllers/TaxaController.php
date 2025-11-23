<?php

namespace App\Http\Controllers;

use App\Models\Taxon;
use Illuminate\Support\Str;

class TaxaController
{
    /**
     * Display taxon details.
     *
     * @param  \App\Models\Taxon  $taxon
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
        $ancestors = $taxon->ancestors()->with('curators')->get();
        $parent = collect([$taxon->load('curators')]);

        $all = $parent
            ->merge($ancestors)
            ->merge($descendants);

        $taxa = $all->map(function ($t) {
            return [
                'id' => $t->id,
                'name' => $t->name,
                'curators' => $t->curators->map(function ($u) {
                    $surname = $u->last_name;
                    $firstNameInitial = Str::upper((Str::substr($u->first_name, 0, 1)));
                    $formattedName = trim($surname.' '.$firstNameInitial.'.');

                    return [
                        'id' => $u->id,
                        'name' => $formattedName,
                    ];
                }),
            ];
        });

        return response()->json([
            'taxa' => $taxa,
        ]);
    }
}
