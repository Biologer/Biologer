<?php

namespace App\Http\Controllers\Api;

use App\Taxon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaxaController extends Controller
{
    public function index()
    {
        return Taxon::where('name', 'like', request('name', '').'%')
            ->orderBy('name', 'asc')
            ->paginate(request('parPage', 10));
    }
}
