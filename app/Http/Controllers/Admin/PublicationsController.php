<?php

namespace App\Http\Controllers\Admin;

use App\Publication;
use App\PublicationType;
use App\Http\Controllers\Controller;

class PublicationsController extends Controller
{
    public function index()
    {
        return view('admin.publications.index');
    }

    public function create()
    {
        return view('admin.publications.create', [
            'publicationTypes' => PublicationType::options(),
        ]);
    }

    public function edit(Publication $publication)
    {
        return view('admin.publications.edit', [
            'publication' => $publication->load('attachment'),
            'publicationTypes' => PublicationType::options(),
        ]);
    }
}
