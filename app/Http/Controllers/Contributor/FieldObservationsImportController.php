<?php

namespace App\Http\Controllers\Contributor;

use App\Import;
use App\Importing\FieldObservationImport;
use App\Importing\ImportStatus;
use Illuminate\Http\Request;

class FieldObservationsImportController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('contributor.field-observations-import.index', [
            'columns' => FieldObservationImport::columns($request->user()),
            'import' => Import::inProgress()->latest()->first(),
            'cancellableStatuses' => collect(ImportStatus::cancellableStatuses()),
        ]);
    }
}
