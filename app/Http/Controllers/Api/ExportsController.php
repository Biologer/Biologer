<?php

namespace App\Http\Controllers\Api;

use App\Models\Export;

class ExportsController
{
    /**
     * Retrieve export details.
     *
     * @param  \App\Models\Export  $export
     * @return \App\Models\Export
     */
    public function show(Export $export)
    {
        abort_unless($export->user_id === auth()->id(), 404);

        return $export;
    }
}
