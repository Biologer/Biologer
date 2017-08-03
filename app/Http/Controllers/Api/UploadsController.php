<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadsController extends Controller
{
    public function store()
    {
        $this->validate(request(), [
            'file' => 'required|image|dimensions:max_width=800,max_height=800',
        ]);

        return response()->json([
            'path' => request()->file('file')->store('uploads/'.auth()->user()->id, 'public'),
        ]);
    }
}
