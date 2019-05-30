<?php

namespace App\Exports\FieldObservations;

class CuratorUnidentifiableFieldObservationsExport extends FieldObservationsExportFactory
{
    /**
     * Custom columns exporter for unidentifiable field observations.
     *
     * @return string
     */
    protected function customType()
    {
        return CuratorUnidentifiableFieldObservationsCustomExport::class;
    }

    /**
     * Darwin Core exporter for unidentifiable field observations.
     *
     * @return string
     */
    protected function darwinCoreType()
    {
        return CuratorUnidentifiableFieldObservationsDarwinCoreExport::class;
    }
}
