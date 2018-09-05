<?php

namespace App\Http\Controllers\Api;

use App\Import;
use App\Rules\ImportFile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Rules\NoImportsInProgress;
use App\Http\Controllers\Controller;

abstract class BaseImportController extends Controller
{
    /**
     * Get import status details.
     *
     * @param  \App\Import  $import
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Import
     */
    public function show(Import $import, Request $request)
    {
        abort_unless($this->canSeeDetails($import, $request), 404);

        return $import;
    }

    /**
     * Check if user can see import details.
     *
     * @param  \App\Import  $import
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Import
     */
    protected function canSeeDetails(Import $import, Request $request)
    {
        return $import->user->is($request->user())
            && $import->type === $this->type();
    }

    /**
     * Handle validation of submit request and starting processing the import.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Import
     */
    public function store(Request $request)
    {
        $importer = $this->type();

        $request->validate([
            'columns' => [
                'bail',
                'required',
                'array',
                Rule::in($importer::availableColumns($request->user())),
                Rule::contain($importer::requiredColumns($request->user())),
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

        return $importer::fromRequest($request);
    }

    /**
     * Get import errors.
     *
     * @param  \App\Import  $import
     * @param  \Illuminate\Http\Request  $request
     * @return
     */
    public function errors(Import $import, Request $request)
    {
        abort_unless($this->canSeeErrors($import, $request), 404);

        return $import->errorsResponse();
    }

    /**
     * Check if user can see import errors.
     *
     * @param  \App\Import  $import
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Import
     */
    protected function canSeeErrors(Import $import, Request $request)
    {
        return $import->user->is($request->user())
            && $import->type === $this->type()
            && $import->status()->validationFailed();
    }

    /**
     * Import type.
     *
     * @return string
     */
    abstract protected function type();
}
