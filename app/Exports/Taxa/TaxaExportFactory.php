<?php

namespace App\Exports\Taxa;

use App\Taxon;
use Illuminate\Http\Request;

class TaxaExportFactory
{
    /**
     * Custom columns exporter for all field observations.
     *
     * @return string
     */
    protected function customType()
    {
        return CustomTaxaExport::class;
    }

    /**
     * Create export instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Export
     */
    public function createFromRequest(Request $request)
    {
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
        return $request->only(array_keys(Taxon::filters()));
    }
}
