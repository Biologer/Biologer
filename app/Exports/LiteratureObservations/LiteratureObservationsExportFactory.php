<?php

namespace App\Exports\LiteratureObservations;

use Illuminate\Http\Request;
use App\LiteratureObservation;

class LiteratureObservationsExportFactory
{
    /**
     * Custom columns exporter for literature observations.
     *
     * @return string
     */
    protected function customType()
    {
        return CustomLiteratureObservationsExport::class;
    }

    /**
     * Darwin Core exporter for literature observations.
     *
     * @return string
     */
    protected function darwinCoreType()
    {
        return DarwinCoreLiteratureObservationsExport::class;
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
        return $request->only(array_keys(LiteratureObservation::filters()));
    }
}
