<?php

namespace App\Http\Controllers\Api;

use App\Exports\FieldObservations\PublicFieldObservationsCustomExport;
use App\Exports\FieldObservations\PublicFieldObservationsExport;
use App\Jobs\PerformExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PublicFieldObservationExportsController
{
    /**
     * @var \App\Exports\FieldObservations\FieldObservationsExportFactory
     */
    protected $fieldObservationsExport;

    /**
     * Construct controller instance.
     *
     * @param  \App\Exports\FieldObservations\PublicFieldObservationsExport  $fieldObservationsExport
     */
    public function __construct(PublicFieldObservationsExport $fieldObservationsExport)
    {
        $this->fieldObservationsExport = $fieldObservationsExport;
    }

    /**
     * Start export of field observations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Export
     */
    public function store(Request $request)
    {
        $this->validateExport($request);

        return tap($this->createExport($request), function ($export) {
            PerformExport::dispatch($export);
        });
    }

    /**
     * Columns that can be exported.
     *
     * @return string
     */
    protected function columns()
    {
        return PublicFieldObservationsCustomExport::availableColumns();
    }

    /**
     * Validate request data needed to start the export.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function validateExport($request)
    {
        $validator = Validator::make($request->all(), [
            'with_header' => ['nullable', 'boolean'],
            'type' => ['required', 'string', 'in:darwin_core,custom'],
        ], [], [
            'columns' => trans('labels.exports.columns'),
            'with_header' => trans('labels.exports.with_header'),
        ]);

        $validator->sometimes('columns', [
            'required', 'array', 'min:1', 'distinct',
            Rule::in($this->columns()),
        ], function ($input) {
            return $input->type === 'custom';
        });

        return $validator->validate();
    }

    /**
     * Create export instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Export
     */
    private function createExport(Request $request)
    {
        return $this->fieldObservationsExport->createFromRequest($request);
    }
}
