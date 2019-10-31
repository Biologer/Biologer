<?php

namespace App\Http\Controllers\Admin;

use App\ConservationDocument;
use App\ConservationLegislation;
use App\Exports\Taxa\CustomTaxaExport;
use App\RedList;
use App\Stage;
use App\Taxon;

class TaxaController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.taxa.index', [
            'exportColumns' => CustomTaxaExport::availableColumnData(),
        ]);
    }

    /**
     * Show page to create taxon.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.taxa.create', [
            'ranks' => collect(Taxon::getRankOptions()),
            'conservationLegislations' => ConservationLegislation::all(),
            'conservationDocuments' => ConservationDocument::all(),
            'redLists' => RedList::all(),
            'redListCategories' => collect(RedList::CATEGORIES),
            'stages' => Stage::all(),
        ]);
    }

    /**
     * Show page to edit taxon.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Taxon $taxon)
    {
        return view('admin.taxa.edit', [
            'taxon' => $taxon->load(['parent', 'redLists', 'conservationLegislations', 'conservationDocuments', 'stages']),
            'ranks' => collect(Taxon::getRankOptions()),
            'conservationLegislations' => ConservationLegislation::all(),
            'conservationDocuments' => ConservationDocument::all(),
            'redLists' => RedList::all(),
            'redListCategories' => collect(RedList::CATEGORIES),
            'stages' => Stage::all(),
        ]);
    }
}
