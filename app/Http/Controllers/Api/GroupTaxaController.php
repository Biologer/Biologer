<?php

namespace App\Http\Controllers\Api;

use App\ViewGroup;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupTaxonResource;

class GroupTaxaController extends Controller
{
    /**
     * Get list of taxa in group with link to first species.
     *
     * @param  \App\ViewGroup  $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ViewGroup $group)
    {
        abort_if($group->isRoot(), 404);

        return GroupTaxonResource::collection(
            $group->allTaxaHigherOrEqualSpeciesRank(request('name'))->paginate()
        );
    }
}
