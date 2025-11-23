<?php

namespace App\Http\Controllers;

use App\Models\ViewGroup;

class GroupSpeciesController
{
    /**
     * Show the list of species in group.
     *
     * @param  \App\Models\ViewGroup  $group
     * @return \Illuminate\View\View
     */
    public function index(ViewGroup $group)
    {
        abort_if($group->isRoot(), 404, 'Invalid group');

        return view('group-species.index', [
            'group' => $group,
            'species' => $group->paginatedSpeciesList(),
        ]);
    }

    /**
     * Show details of the species.
     *
     * @param  \App\Models\ViewGroup  $group
     * @param  int  $species
     * @return \Illuminate\View\View
     */
    public function show(ViewGroup $group, $species)
    {
        abort_if($group->isRoot(), 404, 'Invalid group');

        $species = $group->findSpecies($species);

        $photos = $species->publicPhotos()
            ->filter->public_url
            ->values()
            ->map->forGallery();

        return view('group-species.show', [
            'species' => $species,
            'photos' => $photos,
            'descendants' => $species->lowerRankDescendants(),
        ]);
    }
}
