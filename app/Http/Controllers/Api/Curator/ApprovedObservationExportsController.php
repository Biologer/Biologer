<?php

namespace App\Http\Controllers\Api\Curator;

use App\FieldObservation;
use App\Http\Controllers\Api\BaseExportController;
use App\Exports\CuratorApprovedFieldObservationsExport;

class ApprovedObservationExportsController extends BaseExportController
{
    protected function type()
    {
        return CuratorApprovedFieldObservationsExport::class;
    }

    protected function filters()
    {
        return array_keys(FieldObservation::filters());
    }

    protected function columns()
    {
        return CuratorApprovedFieldObservationsExport::availableColumns();
    }
}
