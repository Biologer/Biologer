<?php

namespace App\Http\Controllers;

use App\Repositories\StatsRepository;

class AboutPagesController
{
    /**
     * @var \App\Repositories\StatsRepository
     */
    private $stats;

    /**
     * @param  \App\Repositories\StatsRepository  $stats
     * @return void
     */
    public function __construct(StatsRepository $stats)
    {
        $this->stats = $stats;
    }

    /**
     * Display page for the local Biologer community.
     *
     * @return \Illuminate\View\View
     */
    public function localCommunity()
    {
        return view('pages.about.local-community', $this->stats->getLocalCommunityData());
    }

    /**
     * Display page for the privacy policy in Biologer community.
     *
     * @return \Illuminate\View\View
     */
    public function privacyPolicy()
    {
        return view('pages.privacy-policy', $this->stats->getAdminData());
    }

    /**
     * Show page with basic stats.
     *
     * @return \Illuminate\View\View
     */
    public function stats()
    {
        return view('pages.about.stats', $this->stats->getStatsData());
    }
}
