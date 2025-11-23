<?php

namespace App\Http\Controllers\Admin;

use App\Models\Publication;
use App\PublicationType;

class PublicationsController
{
    /**
     * Show list of publications.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.publications.index');
    }

    /**
     * Show page to create publication.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.publications.create', [
            'publicationTypes' => PublicationType::options(),
        ]);
    }

    /**
     * Show publication edit page.
     *
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\View\View
     */
    public function edit(Publication $publication)
    {
        return view('admin.publications.edit', [
            'publication' => $publication->load('attachment'),
            'publicationTypes' => PublicationType::options(),
        ]);
    }
}
