<?php

namespace App\Http\Controllers\Admin;

use App\SpecimenCollection;

class SpecimenCollectionsController
{
    public function index()
    {
        return view('admin.specimen-collections.index');
    }

    public function create()
    {
        return view('admin.specimen-collections.create');
    }

    public function edit(SpecimenCollection $specimenCollection)
    {
        return view('admin.specimen-collections.edit', [
            'specimenCollection' => $specimenCollection,
        ]);
    }
}
