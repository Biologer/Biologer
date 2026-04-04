<?php

namespace App\Http\Controllers\Contributor;

use App\TransectSection;
use Illuminate\Http\Request;

class TransectSectionsController
{
    /**
     * Display a list of sections.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contributor.transect-sections.index');
    }

    /**
     * Show transect section details.
     *
     * @param \App\TransectSection $transectSection
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function show(TransectSection $transectSection, Request $request)
    {
        abort_unless($transectSection->isCreatedBy($request->user()), 403);

        return view('contributor.transect-sections.show', [
            'transectSection' => $transectSection->load([
                'transectVisits', 'activity.causer',
            ]),
        ]);
    }

    /**
     * Show form to edit transect section.
     *
     * @param \App\TransectSection $transectSection
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function edit(TransectSection $transectSection, Request $request)
    {
        abort_unless($transectSection->isCreatedBy($request->user()), 403);

        return view('contributor.transect-sections.edit', [
            'transectSection' => $transectSection->load([]),
        ]);
    }
}
