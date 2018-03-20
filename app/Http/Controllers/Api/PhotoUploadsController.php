<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PhotoUploadsController extends Controller
{
    /**
     * 2MB in KB.
     *
     * @var int
     */
    const TWO_MEGABYTES = 2048;

    /**
     * Store uploaded photo.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        request()->validate([
            'file' => [
                'required', 'image', 'max:'.static::TWO_MEGABYTES,
            ],
        ], [
            'file.max' => trans('validation.photo_max_2MB'),
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
