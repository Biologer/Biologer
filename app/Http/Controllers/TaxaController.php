<?php

namespace App\Http\Controllers;

use App\Taxon;

class TaxaController extends Controller
{
    public function show(Taxon $taxon)
    {
        return view('taxa.show', [
            'taxon' => $taxon,
        ]);
    }
}
