<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadsController extends Controller
{
    public function store()
    {
        $this->validate(request(), [
            'file' => 'required|image|dimensions:max_width=800,max_height=800',
        ]);

        return response()->json([
            'file' => basename(request()->file('file')->store('uploads/'.auth()->user()->id, 'public')),
        ]);
    }

    public function destroy()
    {
        if (request()->has('file')) {
            Storage::disk('public')->delete('uploads/'.auth()->user()->id.'/'.request('file'));
        }

        return response()->json(null, 204);
    }
}
