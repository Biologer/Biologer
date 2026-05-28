<?php

namespace App\Http\Controllers\Admin;

use App\CollectionObservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CollectionObservationsController
{
    use AuthorizesRequests;

    /**
     * Show page with list of collection observations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.collection-observations.index', [
            // 'exportColumns' => CustomCollectionObservationsExport::availableColumnData(),
        ]);
    }

    /**
     * Show collection observation details.
     *
     * @param  \App\CollectionObservation  $collectionObservation
     * @return \Illuminate\View\View
     */
    public function show(CollectionObservation $collectionObservation)
    {
        return view('admin.collection-observations.show', [
            'collectionObservation' => $collectionObservation->load([
                'observation.taxon', 'observation.stage', 'collection',
                'activity.causer',
            ]),
        ]);
    }

    /**
     * Show form to create collection observation.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.collection-observations.create');
    }

    /**
     * Show collection observation edit form.
     *
     * @param  \App\CollectionObservation  $collectionObservation
     * @return \Illuminate\View\View
     */
    public function edit(CollectionObservation $collectionObservation)
    {
        $this->authorize('update', $collectionObservation);

        return view('admin.collection-observations.edit', [
            'collectionObservation' => $collectionObservation->load([
                'observation.taxon.stages', 'collection',
            ]),
        ]);
    }
}
