<?php

namespace App\Http\Controllers;

class CitationController extends Controller
{
    public function index()
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

        return view('pages.about.citation', [
            'communityName' => $communityName,
            'platformYear' => $platformYear,
            'platformUrl' => $url,
        ]);
    }
}
