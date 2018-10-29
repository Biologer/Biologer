<?php

namespace App\Exports\FieldObservations;

class ContributorFieldObservationsExport extends FieldObservationsExportFactory
{
    protected function customType()
    {
        return ContributorFieldObservationsCustomExport::class;
    }

    protected function darwinCoreType()
    {
        return ContributorFieldObservationsDarwinCoreExport::class;
    }
}
