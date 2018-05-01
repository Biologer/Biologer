<?php

namespace App\Http\Controllers;

use App\ViewGroup;

class GroupSpeciesController extends Controller
{
    public function show(ViewGroup $group, $species)
    {
        abort_if($group->isRoot(), 404, 'Invalid group');

        return view('group-species.show', [
            'species' => $group->findOrFail($species),
        ]);
    }
}
