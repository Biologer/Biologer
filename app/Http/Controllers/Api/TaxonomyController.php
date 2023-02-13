<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SyncTaxon;
use App\Support\Taxonomy;
use App\Taxon;
use Illuminate\Http\Request;

class TaxonomyController
{
    public function sync(Request $request)
    {
        if (! Taxonomy::checkOrFailUsingTaxonomy()) {
            return response('Error! Local site is not using connection to Taxonomy database.', 400);
        }

        if ($request['key'] != config('biologer.taxonomy_api_key')) {
            return response('Unauthorized!', 401);
        }

        $taxon = Taxon::where('taxonomy_id', $request['taxon']['id'])->firstOr(function () {
            return response("Doesn't exists", 402);
        });

        return response((new SyncTaxon)->update_api($request['taxon'], $taxon, $request['country_ref']), 200);
    }

    public function new(Request $request)
    {
        if (! Taxonomy::checkOrFailUsingTaxonomy()) {
            return response('Error! Local site is not using connection to Taxonomy database.', 400);
        }

        if ($request['key'] != config('biologer.taxonomy_api_key')) {
            return response('Unauthorized!', 401);
        }

        return response((new SyncTaxon)->create_taxon($request['taxon'], $request['country_ref']), 200);
    }
}
