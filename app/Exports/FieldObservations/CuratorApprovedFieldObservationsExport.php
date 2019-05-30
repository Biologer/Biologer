<?php

namespace App\Exports\FieldObservations;

class CuratorApprovedFieldObservationsExport extends FieldObservationsExportFactory
{
    /**
     * Custom columns exporter for approved field observations.
     *
     * @return string
     */
    protected function customType()
    {
        return CuratorApprovedFieldObservationsCustomExport::class;
    }

    /**
     * Darwin Core exporter for approved field observations.
     *
     * @return string
     */
    protected function darwinCoreType()
    {
        return CuratorApprovedFieldObservationsDarwinCoreExport::class;
    }
}
