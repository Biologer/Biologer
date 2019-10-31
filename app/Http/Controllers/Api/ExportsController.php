<?php

namespace App\Http\Controllers\Api;

use App\Export;

class ExportsController
{
    /**
     * Retrieve export details.
     *
     * @param  \App\Export  $export
     * @return \App\Export
     */
    public function show(Export $export)
    {
        abort_unless($export->user_id === auth()->id(), 404);

        return $export;
    }
}
