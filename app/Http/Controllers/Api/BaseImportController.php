<?php

namespace App\Http\Controllers\Api;

use App\Import;
use App\Rules\ImportFile;
use App\Rules\NoImportsInProgress;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

abstract class BaseImportController
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

        $request->validate(
            array_merge([
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
                    new ImportFile($request->input('has_heading', false)),
                    new NoImportsInProgress(),
                ],
                'has_heading' => ['nullable', 'boolean'],
                'user_id' => ['nullable', Rule::exists('users', 'id')],
                'options' => ['nullable', 'array'],
            ], $importer::specificValidationRules()),
            [],
            $importer::validationAttributes()
        );

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
