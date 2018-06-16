<?php

namespace App\Http\Controllers\Api;

use App\Export;
use App\Http\Controllers\Controller;

class ExportsController extends Controller
{
    public function show(Export $export)
    {
        abort_unless($export->user_id === auth()->id(), 404);

        return $export;
    }
}
