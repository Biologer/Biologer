<?php

namespace App\Exports\FieldObservations;

class CuratorPendingFieldObservationsExport extends FieldObservationsExportFactory
{
    protected function customType()
    {
        return CuratorPendingFieldObservationsCustomExport::class;
    }

    protected function darwinCoreType()
    {
        return CuratorPendingFieldObservationsDarwinCoreExport::class;
    }
}
