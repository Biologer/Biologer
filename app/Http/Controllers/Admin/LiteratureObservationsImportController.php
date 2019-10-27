<?php

namespace App\Http\Controllers\Admin;

use App\Import;
use App\Importing\ImportStatus;
use App\Importing\LiteratureObservationImport;
use Illuminate\Http\Request;

class LiteratureObservationsImportController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('admin.literature-observations-import.index', [
            'columns' => LiteratureObservationImport::columns($request->user()),
            'import' => Import::inProgress()->latest()->first(),
            'cancellableStatuses' => collect(ImportStatus::cancellableStatuses()),
        ]);
    }
}
