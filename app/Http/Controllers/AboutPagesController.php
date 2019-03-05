<?php

namespace App\Http\Controllers;

use App\User;
use App\FieldObservation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;

class AboutPagesController extends Controller
{
    public function localCommunity()
    {
        // We cache data for local community page so we don't hit database more than needed.
        $viewData = Cache::remember('localCommunityPageData', now()->addMinutes(5), function () {
            return $this->getLocalCommunityData();
        });

        return view('pages.about.local-community', $viewData);
    }

    /**
     * Retrieve data required for local community page.
     *
     * @return array
     */
    private function getLocalCommunityData()
    {
        $curators = User::curators()->with(['curatedTaxa' => function ($query) {
            $query->orderByAncestry();
        }])->sortByName()->get();

        $taxonomicGroupsCount = Collection::make(
            $curators->pluck('curatedTaxa')->flatten(1)
        )->unique()->count();

        return [
            'usersCount' => User::count(),
            'admins' => User::admins()->sortByName()->get(),
            'curators' => $curators,
            'observationsCount' => FieldObservation::approved()->count(),
            'taxonomicGroupsCount' => $taxonomicGroupsCount,
        ];
    }
}
