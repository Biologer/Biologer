<?php

namespace App\Repositories;

use App\FieldObservation;
use App\Observation;
use App\User;
use App\ViewGroup;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class StatsRepository
{
    /**
     * Retrieve data required for local community page.
     *
     * @return array
     */
    public function getLocalCommunityData()
    {
        return Cache::remember('localCommunityPageData', now()->addMinutes(5), function () {
            return $this->getLocalCommunityDataFromDb();
        });
    }

    /**
     * Retrieve data required for privacy policy page.
     *
     * @return array
     */
    public function getAdminData()
    {
        return Cache::remember('privacyPolicyPageData', now()->addMinutes(5), function () {
            return $this->getAdminDataFromDb();
        });
    }

    /**
     * Retrieve data required for privacy policy page from DB.
     *
     * @return array
     */
    private function getLocalCommunityDataFromDb()
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

    /**
     * Retrieve data required for privacy policy page from DB.
     *
     * @return array
     */
    private function getAdminDataFromDb()
    {
        $admins = User::admins()->sortByName()->get();
        $obfuscated_data = [];

        foreach ($admins as $admin) {
            $obfuscated_data[] = [
                'full_name' => $admin['full_name'],
                'institution' => $admin['institution'],
                'email' => Str::replace('@', ' [at] ', $admin['email']),
            ];
        }

        return [
            'admins' => $obfuscated_data,
        ];
    }

    /**
     * We cache stats data, so we don't hit database more than needed.
     *
     * @return array
     */
    public function getStatsData()
    {
        return Cache::remember('statsPageData', now()->addMinutes(15), function () {
            return $this->getStatsDataFromDb();
        });
    }

    /**
     * Get stats to show on the page.
     *
     * @return array
     */
    private function getStatsDataFromDb()
    {
        $topUsers = User::withCount('observationsOfTypeField as field_observations_count')
            ->has('observationsOfTypeField', '>', 0)
            ->orderBy('field_observations_count', 'desc')
            ->limit(10)
            ->get();

        $topCurators = User::curators()
            ->withCount('fieldObservationsIdentified')
            ->has('fieldObservationsIdentified', '>', 0)
            ->orderBy('field_observations_identified_count', 'desc')
            ->limit(10)
            ->get();

        $groups = ViewGroup::roots()->with(['groups' => function ($query) {
            $query->select(['*'])->withObservationsCount();
        }])->get();

        $observationsByYear = Observation::query()
            ->select(['year'])
            ->selectRaw('count(year) as observations_count')
            ->whereNotNull('year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->limit(10)
            ->getQuery()
            ->get();

        return [
            'topUsers' => $topUsers,
            'topCurators' => $topCurators,
            'groups' => $groups,
            'observationsByYear' => $observationsByYear,
        ];
    }
}
