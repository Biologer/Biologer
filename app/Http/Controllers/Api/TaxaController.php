<?php

namespace App\Http\Controllers\Api;

use App\Taxon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaxaController extends Controller
{
    public function index()
    {
        list($sortField, $sortOrder) = explode('.', request('sort_by', 'name.asc'));

        $taxa = Taxon::orderBy($sortField, $sortOrder)->orderBy('id');

        if (request()->has('name')) {
            $taxa = $taxa->where('name', 'like', request('name', '').'%');
        }

        if (request('all')) {
            return $taxa->get();
        }

        return $taxa->paginate(
            request('per_page', 15)
        );
    }
}
