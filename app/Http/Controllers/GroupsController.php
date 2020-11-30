<?php

namespace App\Http\Controllers;

use App\ViewGroup;
use Illuminate\Support\Facades\Cache;

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
            'rootGroups' => Cache::rememberForever(ViewGroup::CACHE_GROUPS_WITH_FIRST_SPECIES, function () {
                return ViewGroup::roots()->with(['groups' => function ($query) {
                    $query->withFirstSpecies();
                }])->get();
            }),
        ]);
    }
}
