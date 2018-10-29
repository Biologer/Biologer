<?php

namespace App\Exports\FieldObservations;

class CuratorUnidentifiableFieldObservationsExport extends FieldObservationsExportFactory
{
    protected function customType()
    {
        return CuratorUnidentifiableFieldObservationsCustomExport::class;
    }

    protected function darwinCoreType()
    {
        return CuratorUnidentifiableFieldObservationsDarwinCoreExport::class;
    }
}
