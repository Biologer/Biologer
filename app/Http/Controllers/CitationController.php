<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Taxon;
use Carbon\Carbon;

class CitationController extends Controller
{
    /**
     * Returns the list of curators and formated citation for the Taxon ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCuratorCitationData(Request $request)
    {
        $taxonId = $request->input('taxon_id');

        if (! $taxon = Taxon::with('curators')->find($taxonId)) {
            return \response()->json(['error' => 'Taxon not found'], 404);
        }

        // 1. Get the currator
        $editors = $taxon->curators->pluck('name')->unique()->sort()->implode(', ');

        // 2. Get other local data
        $url = config('biologer.community.address');
        $communityName = __('about.community_default');
        $platformYear = __('about.community_unknown');

        if (\str_contains($url, 'biologer.rs')) {
            $communityName = __('about.community_rs');
            $platformYear = '2018';
        } elseif (\str_contains($url, 'biologer.hr')) {
            $communityName = __('about.community_hr');
            $platformYear = '2019';
        } elseif (\str_contains($url, 'biologer.ba')) {
            $communityName = __('about.community_bs');
            $platformYear = '2019';
        } elseif (\str_contains($url, 'biologer.me')) {
            $communityName = __('about.community_me');
            $platformYear = '2023';
        }

        // 3. Format the data
        $citationText = \sprintf(
            '%s (%s) %s (%s). %s: %s. %s: %s.',
            $editors ?: $communityName,
            $platformYear,
            __('about.community_desc'),
            $taxon->name,
            __('about.community_url'),
            $url,
            __('about.community_assessed'),
            \Carbon\Carbon::now()->translatedFormat('d.m.Y.')
        );

        return response()->json([
            'editors' => $editors,
            'taxonName' => $taxon->name,
            'citation' => $citationText,
        ]);
    }
}
