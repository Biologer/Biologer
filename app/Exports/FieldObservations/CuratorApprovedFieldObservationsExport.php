<?php

namespace App\Exports\FieldObservations;

class CuratorApprovedFieldObservationsExport extends FieldObservationsExportFactory
{
    protected function customType()
    {
        return CuratorApprovedFieldObservationsCustomExport::class;
    }

    protected function darwinCoreType()
    {
        return CuratorApprovedFieldObservationsDarwinCoreExport::class;
    }
}
