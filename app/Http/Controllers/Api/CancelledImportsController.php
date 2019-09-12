<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Import;
use App\Importing\ImportStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CancelledImportsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'import_id' => ['required', Rule::exists('imports', 'id')->where(function ($query) {
                $query->whereIn('status', ImportStatus::cancellableStatuses());
            })],
        ]);

        return Import::find($request->input('import_id'))->cancel();
    }
}
