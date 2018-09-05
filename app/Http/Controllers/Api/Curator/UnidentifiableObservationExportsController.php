<?php

namespace App\Http\Controllers\Api\Curator;

use App\FieldObservation;
use App\Http\Controllers\Api\BaseExportController;
use App\Exports\CuratorUnidentifiableFieldObservationsExport;

class UnidentifiableObservationExportsController extends BaseExportController
{
    protected function type()
    {
        return CuratorUnidentifiableFieldObservationsExport::class;
    }

    protected function filters()
    {
        return array_keys(FieldObservation::filters());
    }

    protected function columns()
    {
        return CuratorUnidentifiableFieldObservationsExport::availableColumns();
    }
}
