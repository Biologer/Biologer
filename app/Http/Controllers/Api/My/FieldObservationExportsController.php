<?php

namespace App\Http\Controllers\Api\My;

use App\FieldObservation;
use App\Jobs\PerformExport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Exports\ContributorFieldObservationsExport;
use App\Exports\ContributorFieldObservationsDarwinCoreExport;

class FieldObservationExportsController extends Controller
{
    /**
     * Start export of contributors field observations.
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

    private function validateExport($request)
    {
        $v = Validator::make($request->all(), [
            'with_header' => ['nullable', 'boolean'],
            'type' => ['required', 'string', 'in:darwin_core,custom']
        ], [], [
            'columns' => trans('labels.exports.columns'),
            'with_header' => trans('labels.exports.with_header'),
        ]);

        $v->sometimes('columns', [
            'required', 'array', 'min:1', 'distinct',
            Rule::in(ContributorFieldObservationsExport::availableColumns())
        ], function ($input) {
            return $input->type === 'custom';
        });

        return $v->validate();
    }

    /**
     * Create export instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Export
     */
    private function createExport($request)
    {
        if ($request->input('type', 'custom') === 'darwin_core') {
            return ContributorFieldObservationsDarwinCoreExport::createFiltered(
                $this->getFiltersFromRequest($request)
            );
        }

        return ContributorFieldObservationsExport::create(
            $request->input('columns'),
            $this->getFiltersFromRequest($request),
            $request->input('with_header', false)
        );
    }

    /**
     * Extract filters from request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function getFiltersFromRequest($request)
    {
        return $request->only(array_keys(FieldObservation::filters()));
    }
}
