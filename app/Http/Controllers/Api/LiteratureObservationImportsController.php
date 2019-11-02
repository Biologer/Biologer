<?php

namespace App\Http\Controllers\Api;

use App\Importing\LiteratureObservationImport;

class FieldObservationImportsController extends BaseImportController
{
    /**
     * Import type.
     *
     * @return string
     */
    protected function type()
    {
        return LiteratureObservationImport::class;
    }
}
