<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GroupTaxonResource;
use App\ViewGroup;

class GroupTaxaController
{
    /**
     * Get list of taxa in group with link to first species.
     *
     * @param  \App\ViewGroup  $group
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(ViewGroup $group)
    {
        abort_if($group->isRoot(), 404);

        return GroupTaxonResource::collection(
            $group->allTaxaHigherOrEqualSpeciesRank(request('name'))->paginate()
        );
    }
}
