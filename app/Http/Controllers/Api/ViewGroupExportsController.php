<?php

namespace App\Http\Controllers\Api;

use App\Exports\ViewGroups\ViewGroupsExport;
use App\Exports\ViewGroups\ViewGroupsExportFactory;
use App\Jobs\PerformExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ViewGroupExportsController
{
    /**
     * @var \App\Exports\ViewGroups\ViewGroupsExport
     */
    protected $viewGroupsExport;

    /**
     * Construct controller instance.
     *
     * @param  \App\Exports\ViewGroups\ViewGroupsExportFactory  $viewGroupsExport
     * @return void
     */
    public function __construct(ViewGroupsExportFactory $viewGroupsExport)
    {
        $this->viewGroupsExport = $viewGroupsExport;
    }

    /**
     * Start export of view groups.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Export
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
        return ViewGroupsExport::availableColumns();
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
            'type' => ['required', 'string', 'in:custom'],
        ], [], [
            'columns' => trans('labels.exports.columns'),
            'with_header' => trans('labels.exports.with_header'),
        ]);

        $validator->sometimes('columns', [
            'required', 'array', 'min:1', 'distinct', Rule::in($this->columns()),
        ], function ($input) {
            return $input->type === 'custom';
        });

        return $validator->validate();
    }

    /**
     * Create export instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Export
     */
    private function createExport(Request $request)
    {
        return $this->viewGroupsExport->createFromRequest($request);
    }
}
