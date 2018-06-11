<?php

namespace App\Http\Controllers\Api;

use App\Import;
use App\Rules\ImportFile;
use App\Importing\BaseImport;
use Illuminate\Validation\Rule;
use App\Rules\NoImportsInProgress;

abstract class BaseImportController
{
    /**
     * @var \App\Importing\BaseImport
     */
    protected $importer;

    /**
     * @param \App\Importing\BaseImport $importer
     */
    public function __construct(BaseImport $importer)
    {
        $this->importer = $importer;
    }

    /**
     * Get import status details.
     *
     * @param  \App\Import  $import
     * @return \App\Import
     */
    public function show(Import $import)
    {
        abort_unless($import->user->is(auth()->user())
            && $import->type === get_class($this->importer), 404);

        return $import;
    }

    /**
     * Handle validation of submit request and starting processing the import.
     *
     * @return \App\Import
     */
    public function store()
    {
        request()->validate([
            'columns' => [
                'bail',
                'required',
                'array',
                Rule::in($this->importer->columns()),
                Rule::contain($this->importer->requiredColumns()),
            ],
            'file' => [
                'bail',
                'required',
                'mimes:csv,txt',
                'max:'.config('biologer.max_upload_size'),
                new ImportFile(),
                new NoImportsInProgress(),
            ],
        ]);

        return $this->importer->queue(
            request()->file('file')->store('imports'),
            request('columns', [])
        );
    }
}
