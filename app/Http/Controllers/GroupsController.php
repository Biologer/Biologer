<?php

namespace App\Http\Controllers;

use App\ViewGroup;

class GroupsController
{
    /**
     * Display page to browse groups.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('groups.index', [
            'rootGroups' => ViewGroup::roots()->with(['groups' => function ($query) {
                $query->withFirstSpecies();
            }])->get(),
        ]);
    }
}
