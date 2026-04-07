<?php

namespace App\Http\Controllers\Admin;

use App\Services\TaxonomyService;
use App\Support\Taxonomy;
use App\Taxon;

class TaxonomyController
{
    public function __construct(private readonly TaxonomyService $taxonomy)
    {
    }

    /**
     * Display taxonomy options.
     */
    public function index()
    {
        return view('admin.taxonomy.index', [
            'not_synced' => Taxon::where('taxonomy_id', null)->count(),
            'synced' => Taxon::whereNotNull('taxonomy_id')->count(),
        ]);
    }

    public function check()
    {
        return $this->taxonomy->check();
    }

    public function connect()
    {
        return $this->taxonomy->connect();
    }

    public function disconnect()
    {
        return $this->taxonomy->disconnect();
    }

    public function syncTaxon(): string
    {
        return $this->taxonomy->syncAll();
    }
}
