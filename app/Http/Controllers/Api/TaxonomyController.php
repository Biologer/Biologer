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

        $taxon = Taxon::where('taxonomy_id', $request['taxon']['id'])->first();

        if ($taxon) {
            return response((new SyncTaxon)->updateTaxon($request['taxon'], $taxon, $request['country_ref']), 200);
        }

        return response((new SyncTaxon)->createTaxon($request['taxon'], $request['country_ref']), 200);
    }

    public function remove(Request $request)
    {
        if (! Taxonomy::checkOrFailUsingTaxonomy()) {
            return response('Error! Local site is not using connection to Taxonomy database.', 400);
        }

        if ($request['key'] != config('biologer.taxonomy_api_key')) {
            return response('Unauthorized!', 401);
        }

        Taxon::where(['taxonomy_id' => $request['taxon']['id']])->delete();

        return response()->json(null, 204);
    }

    public function deselect(Request $request)
    {
        if (! Taxonomy::checkOrFailUsingTaxonomy()) {
            return response('Error! Local site is not using connection to Taxonomy database.', 400);
        }

        if ($request['key'] != config('biologer.taxonomy_api_key')) {
            return response('Unauthorized!', 401);
        }

        return response(Taxon::where(['taxonomy_id' => $request['taxon']['id']])->update(['taxonomy_id' => null]), 200);
    }
}
