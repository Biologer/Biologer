<?php

namespace App\Http\Controllers;

use App\Taxon;

class TaxaController extends Controller
{
    /**
     * Display taxon details.
     *
     * @param  \App\Taxon  $taxon
     * @return \Illuminate\View\View
     */
    public function show(Taxon $taxon)
    {
        return view('taxa.show', [
            'taxon' => $taxon,
        ]);
    }
}
