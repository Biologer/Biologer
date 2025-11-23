<?php

namespace App\Http\Controllers\Admin;

use App\Models\ConservationDocument;
use App\Models\ConservationLegislation;
use App\Exports\Taxa\AdminTaxaExport;
use App\Exports\Taxa\CustomTaxaExport;
use App\Models\RedList;
use App\Models\Stage;
use App\Support\Taxonomy;
use App\Models\Taxon;

class TaxaController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.taxa.index', [
            'exportColumns' => CustomTaxaExport::availableColumnData(),
            'adminExportColumns' => AdminTaxaExport::availableColumnData(),
            'ranks' => Taxon::getRankOptions(),
            'taxonomy' => Taxonomy::isUsingTaxonomy(),
            'taxonomyLink' => Taxonomy::getLink(),
        ]);
    }

    /**
     * Show page to create taxon.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.taxa.create', [
            'ranks' => Taxon::getRankOptions(),
            'conservationLegislations' => ConservationLegislation::all(),
            'conservationDocuments' => ConservationDocument::all(),
            'redLists' => RedList::all(),
            'redListCategories' => collect(RedList::CATEGORIES),
            'stages' => Stage::all(),
            'taxonomy' => Taxonomy::isUsingTaxonomy(),
        ]);
    }

    /**
     * Show page to edit taxon.
     *
     * @param  \App\Models\Taxon  $taxon
     * @return \Illuminate\View\View
     */
    public function edit(Taxon $taxon)
    {
        return view('admin.taxa.edit', [
            'taxon' => $taxon->load(['parent', 'redLists', 'conservationLegislations', 'conservationDocuments', 'stages']),
            'ranks' => Taxon::getRankOptions(),
            'conservationLegislations' => ConservationLegislation::all(),
            'conservationDocuments' => ConservationDocument::all(),
            'redLists' => RedList::all(),
            'redListCategories' => collect(RedList::CATEGORIES),
            'stages' => Stage::all(),
            'synonyms' => $taxon->load(['synonyms']),
            'taxonomy' => Taxonomy::isUsingTaxonomy(),
        ]);
    }
}
