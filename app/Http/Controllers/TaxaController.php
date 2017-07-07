<?php

namespace App\Http\Controllers;

use App\Taxon;
use Illuminate\Http\Request;

class TaxaController extends Controller
{
    public function show(Taxon $taxon)
    {
        return view('taxa.show', [
            'taxon' => $taxon,
        ]);
    }
}
