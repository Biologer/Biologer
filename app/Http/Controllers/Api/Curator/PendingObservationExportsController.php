<?php

namespace App\Http\Controllers\Api\Curator;

use App\FieldObservation;
use App\Http\Controllers\Api\BaseExportController;
use App\Exports\CuratorPendingFieldObservationsExport;

class PendingObservationExportsController extends BaseExportController
{
    protected function type()
    {
        return CuratorPendingFieldObservationsExport::class;
    }

    protected function filters()
    {
        return array_keys(FieldObservation::filters());
    }

    protected function columns()
    {
        return CuratorPendingFieldObservationsExport::availableColumns();
    }
}
