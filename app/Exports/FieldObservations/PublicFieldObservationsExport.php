<?php

namespace App\Exports\FieldObservations;

class PublicFieldObservationsExport extends FieldObservationsExportFactory
{
    /**
     * Custom columns exporter for approved field observations.
     *
     * @return string
     */
    protected function customType()
    {
        return PublicFieldObservationsCustomExport::class;
    }

    /**
     * Darwin Core exporter for approved field observations.
     *
     * @return string
     */
    protected function darwinCoreType()
    {
        return PublicFieldObservationsDarwinCoreExport::class;
    }
}
