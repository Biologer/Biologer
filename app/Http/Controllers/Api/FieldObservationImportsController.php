<?php

namespace App\Http\Controllers\Api;

use App\Importing\FieldObservationImport;

class FieldObservationImportsController extends BaseImportController
{
    /**
     * Import type.
     *
     * @return string
     */
    protected function type()
    {
        return FieldObservationImport::class;
    }
}
