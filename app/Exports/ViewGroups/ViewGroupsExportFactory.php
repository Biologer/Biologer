<?php

namespace App\Exports\ViewGroups;

use App\Taxon;
use Illuminate\Http\Request;

class ViewGroupsExportFactory
{
    /**
     * Custom columns exporter for all view groups.
     *
     * @return string
     */
    protected function customType()
    {
        return ViewGroupsExport::class;
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
        return $request->only(array_keys((new Taxon())->filters()));
    }
}
