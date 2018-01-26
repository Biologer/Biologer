<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PhotoUploadsController extends Controller
{
    /**
     * Store uploaded photo.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        request()->validate([
            'file' => 'required|image|dimensions:max_width=800,max_height=800',
        ]);

        return response()->json([
            'file' => basename(request()->file('file')->store('uploads/'.auth()->user()->id, 'public')),
        ]);
    }

    /**
     * Delete uploaded photo.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        if (request()->has('file')) {
            Storage::disk('public')->delete('uploads/'.auth()->user()->id.'/'.request('file'));
        }

        return response()->json(null, 204);
    }
}
