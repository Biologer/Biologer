<?php

namespace App\Http\Controllers\Api;

use App\Importing\LiteratureObservationImport;

class LiteratureObservationImportsController extends BaseImportController
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
