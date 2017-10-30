<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaxaController extends Controller
{
    public function index()
    {
        return view('admin.taxa.index');
    }

    public function edit($id)
    {
        return view('admin.taxa.edit');
    }
}
