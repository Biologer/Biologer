<?php

namespace App\Http\Controllers\Admin;

use App\ConservationDocument;
use App\ConservationLegislation;
use App\Http\Requests\SyncTaxon;
use App\RedList;
use App\Support\Taxonomy;
use App\Taxon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class TaxonomyController
{
    /**
     * Display taxonomy options.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.taxonomy.index', [
            'not_synced' => Taxon::where('taxonomy_id', null)->count(),
            'synced' => Taxon::whereNotNull('taxonomy_id')->count(),
        ]);
    }

    /**
     *  Check if connection is possible to make.
     */
    public function check()
    {
        $link = Taxonomy::checkOrFailUsingTaxonomy();
        if (! $link) {
            return 'Failed to connect! Check .env file on server!';
        }

        try {
            $response = Http::post($link.'/api/taxonomy/check', [
                'key' => config('biologer.taxonomy_api_key'),
            ]);
        } catch (Exception $e) {
            return 'Failed to connect! No connection to server.';
        }

        if ($response->status() == 200) {
            return "Check for $link is identified as {$response->body()}";
        } else {
            return 'Unauthorized';
        }
    }

    /**
     * Connects to Taxonomy.
     */
    public function connect()
    {
        $link = Taxonomy::checkOrFailUsingTaxonomy();
        if (! $link) {
            return 'Failed to connect! Check .env file on server!';
        }

        $data['key'] = config('biologer.taxonomy_api_key');
        $data['red_lists'] = RedList::all()->toArray();
        $data['docs'] = ConservationDocument::all()->toArray();
        $data['legs'] = ConservationLegislation::all()->toArray();

        try {
            $response = Http::post($link.'/api/taxonomy/connect', $data);
        } catch (Exception $e) {
            return 'Failed to connect! No connection to server.';
        }

        return $response->body();
    }

    /**
     * Disconnects from Taxonomy.
     */
    public function disconnect()
    {
        $link = Taxonomy::checkOrFailUsingTaxonomy();
        if (! $link) {
            return 'Failed to connect! Check .env file on server!';
        }

        $data['key'] = config('biologer.taxonomy_api_key');

        try {
            $response = Http::post($link.'/api/taxonomy/disconnect', $data);
        } catch (Exception $e) {
            return 'Failed to connect! No connection to server.';
        }

        if ($response->status() == 200) {
            $taxa = Taxon::whereNotNull('taxonomy_id')->get();
            foreach ($taxa as $taxon) {
                $taxon->update(['taxonomy_id' => null]);
                $taxon->save();
            }

            return 'Disconnected from Taxonomy';
        }

        return 'Failed to disconnect from Taxonomy!';
    }

    /**
     * Find taxonomy ID for all taxa that are not connected and update taxa from Taxonomy database.
     * Once Taxonomy database get request from specific taxon, it will send updates when occurred.
     */
    public function sync()
    {
        $link = Taxonomy::checkOrFailUsingTaxonomy();
        if (! $link) {
            return 'Error! Local site is not using connection to Taxonomy database.';
        }

        $synced = 0;
        $all_taxa = Taxon::where('taxonomy_id', null)->get();

        foreach ($all_taxa->chunk(1000) as $taxa) {
            $data['taxa'] = [];
            $data['key'] = config('biologer.taxonomy_api_key');

            foreach ($taxa as $taxon) {
                $data['taxa'][$taxon->id]['name'] = $taxon->name;
                $data['taxa'][$taxon->id]['rank'] = $taxon->rank;
                if ($taxon->ancestors_names == '') {
                    $data['taxa'][$taxon->id]['ancestor_name'] = '';

                    continue;
                }
                $data['taxa'][$taxon->id]['ancestor_name'] = Arr::first(explode(',', $taxon->ancestors_names)).'%';
            }

            try {
                $response = Http::post($link.'/api/taxonomy/sync', $data);
            } catch (Exception $e) {
                return 'Failed to connect! No connection to server.';
            }

            if ($response->status() != 200) {
                return '<p>Error! Data not retrieved.</p><p>'.$response->status().'</p><p>'.$response->body().'</p>';
            }

            $returned_taxa = $response['taxa'];
            $country_ref = $response['country_ref'];
            foreach ($returned_taxa as $id => $data) {
                if ($data['response'] == '') {
                    continue;
                }
                $synced += 1;
                $taxon = Taxon::find($id);

                # Update current taxon with Taxonomy data
                (new SyncTaxon)->update($data['response'], $taxon, $country_ref);
            }
        }

        $not_synced = Taxon::where('taxonomy_id', null)->count();
        $synced_total = Taxon::whereNotNull('taxonomy_id')->count();

        return 'Sync done for '.$synced.' taxa. Not synced '.$not_synced.'. All times synced '.$synced_total;
    }

    public function syncParent(Taxon $parent)
    {
        $link = Taxonomy::checkOrFailUsingTaxonomy();
        if (! $link) {
            return 'Error! Local site is not using connection to Taxonomy database.';
        }

        $data['taxa'] = [];
        $data['key'] = config('biologer.taxonomy_api_key');

        $data['taxa'][$parent->id]['name'] = $parent->name;
        $data['taxa'][$parent->id]['rank'] = $parent->rank;
        if ($parent->ancestors_names == '') {
            $data['taxa'][$parent->id]['ancestor_name'] = '';
        } else {
            $data['taxa'][$parent->id]['ancestor_name'] = Arr::first(explode(',', $parent->ancestors_names)).'%';
        }

        try {
            $response = Http::post($link.'/api/taxonomy/sync', $data);
        } catch (Exception $e) {
            return 'Failed to connect! No connection to server.';
        }

        $returned_taxa = $response['taxa'];
        $country_ref = $response['country_ref'];

        foreach ($returned_taxa as $id => $data) {
            if ($data['response'] == '') {
                continue;
            }
            $taxon = Taxon::find($id);

            # Update current taxon with Taxonomy data
            (new SyncTaxon)->update($data['response'], $taxon, $country_ref);
        }
    }
}
