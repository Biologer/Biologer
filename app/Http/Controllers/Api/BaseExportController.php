<?php

namespace App\Http\Controllers\Api;

use App\Jobs\PerformExport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

abstract class BaseExportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'columns' => [
                'required',
                'array',
                'min:1',
                'distinct',
                Rule::in($this->columns()),
            ],
            'with_header' => ['boolean'],
        ], [], [
            'columns' => trans('labels.exports.columns'),
            'with_header' => trans('labels.exports.with_header'),
        ]);

        $type = $this->type();

        $export = $type::create(
            $request->input('columns'),
            $request->only($this->filters()),
            $request->input('with_header', false)
        );

        PerformExport::dispatch($export);

        return $export;
    }

    /**
     * Export type.
     *
     * @return string
     */
    abstract protected function type();

    /**
     * Filters available during export.
     *
     * @return array
     */
    abstract protected function filters();

    /**
     * Columns that can be exported.
     *
     * @return string
     */
    abstract protected function columns();
}
