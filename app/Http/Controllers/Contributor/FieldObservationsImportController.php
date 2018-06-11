<?php

namespace App\Http\Controllers\Contributor;

use App\Import;
use App\Http\Controllers\Controller;
use App\Importing\FieldObservationImport;

class FieldObservationsImportController extends Controller
{
    /**
     * @var \App\Importing\FieldObservationImport
     */
    protected $fieldObservationImport;

    /**
     * Create controller instance.
     *
     * @param  \App\Importing\FieldObservationImport  $fieldObservationImport
     * @return void
     */
    public function __construct(FieldObservationImport $fieldObservationImport)
    {
        $this->fieldObservationImport = $fieldObservationImport;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contributor.field-observations-import.index', [
            'columns' => $this->fieldObservationImport->allColumns(),
            'import' => Import::inProgress()->latest()->first(),
        ]);
    }
}
