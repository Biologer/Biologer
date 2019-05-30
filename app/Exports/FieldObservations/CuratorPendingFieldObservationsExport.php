<?php

namespace App\Exports\FieldObservations;

class CuratorPendingFieldObservationsExport extends FieldObservationsExportFactory
{
    /**
     * Custom columns exporter for pending field observations.
     *
     * @return string
     */
    protected function customType()
    {
        return CuratorPendingFieldObservationsCustomExport::class;
    }

    /**
     * Darwin Core exporter for pending field observations.
     *
     * @return string
     */
    protected function darwinCoreType()
    {
        return CuratorPendingFieldObservationsDarwinCoreExport::class;
    }
}
