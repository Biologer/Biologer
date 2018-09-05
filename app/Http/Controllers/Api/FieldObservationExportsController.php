<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use App\Exports\FieldObservationsExport;

class FieldObservationExportsController extends BaseExportController
{
    protected function type()
    {
        return FieldObservationsExport::class;
    }

    protected function filters()
    {
        return array_keys(FieldObservation::filters());
    }

    protected function columns()
    {
        return FieldObservationsExport::availableColumns();
    }
}
