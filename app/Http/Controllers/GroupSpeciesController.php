<?php

namespace App\Http\Controllers;

use App\ViewGroup;

class GroupSpeciesController extends Controller
{
    /**
     * Show the list of species in group.
     *
     * @param  \App\ViewGroup  $group
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
     * @param  \App\ViewGroup  $group
     * @param  int  $species
     * @return \Illuminate\View\View
     */
    public function show(ViewGroup $group, $species)
    {
        abort_if($group->isRoot(), 404, 'Invalid group');

        return view('group-species.show', [
            'species' => $group->findOrFail($species),
        ]);
    }
}
