<?php

namespace App\Http\Controllers;

use App\Export;

class ExportDownloadController
{
    /**
     * Download exported file.
     *
     * @param  \App\Export  $export
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function __invoke(Export $export)
    {
        abort_unless($export->user_id === auth()->id(), 404);

        return $export->download();
    }
}
