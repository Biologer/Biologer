<?php

namespace App\Exports\FieldObservations;

use App\FieldObservation;
use Illuminate\Http\Request;

class FieldObservationsExportFactory
{
    /**
     * Custom columns exporter for all field observations.
     *
     * @return string
     */
    protected function customType()
    {
        return CustomFieldObservationsExport::class;
    }

    /**
     * Darwin Core exporter for all field observations.
     *
     * @return string
     */
    protected function darwinCoreType()
    {
        return DarwinCoreFieldObservationsExport::class;
    }

    /**
     * Create export instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Export
     */
    public function createFromRequest(Request $request)
    {
        if ($request->input('type', 'custom') === 'darwin_core') {
            $type = $this->darwinCoreType();

            return $type::createFiltered(
                $this->getFiltersFromRequest($request)
            );
        }

        $type = $this->customType();

        return $type::create(
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
    private function getFiltersFromRequest(Request $request)
    {
        return $request->only(array_keys(FieldObservation::filters()));
    }
}
