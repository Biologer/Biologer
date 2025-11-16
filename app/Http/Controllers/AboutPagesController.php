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

    /**
     * Display page for the citation information.
     *
     * @return \Illuminate\View\View
     */
    public function citation()
    {

        $citationData = $this->getWebCitationData();

        return view('pages.about.citation', $citationData);
    }

    /**
     * Determines the correct community name and start year based on the configured URL.
     *
     * @return array
     */
    private function getWebCitationData(): array
    {
        $url = config('biologer.community.address');
        $communityName = __('about.community_default'); // Defaults to "Biologer Community"
        $platformYear = __('about.community_unknown');

        // Use a switch or if/else on the URL for determination
        if (str_contains($url, 'biologer.rs')) {
            $communityName = __('about.community_rs');
            $platformYear = '2018';
        } elseif (str_contains($url, 'biologer.hr')) {
            $communityName = __('about.community_hr');
            $platformYear = '2019';
        } elseif (str_contains($url, 'biologer.ba')) {
            $communityName = __('about.community_ba');
            $platformYear = '2019';
        } elseif (str_contains($url, 'biologer.me')) {
            $communityName = __('about.community_me');
            $platformYear = '2023';
        }

        // Ensure the URL is also returned for the view, even though it's in config, 
        // this keeps the citation block's data contained in one array.
        return [
            'communityName' => $communityName,
            'platformYear' => $platformYear,
            'platformUrl' => $url, // Pass the URL along
        ];
    }
}
