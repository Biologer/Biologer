<?php

namespace App\Http\Controllers\Contributor;

use App\Import;
use Illuminate\Http\Request;
use App\Importing\ImportStatus;
use App\Http\Controllers\Controller;
use App\Importing\FieldObservationImport;

class FieldObservationsImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
