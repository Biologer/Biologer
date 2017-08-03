<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    public static function store($path, $data)
    {
        return tap(static::create($data), function ($photo) use ($path) {
            $photo->path = "photos/{$photo->id}/".basename($path);
            Storage::disk('public')->move($path, $photo->path);
            $photo->url = Storage::disk('public')->url($photo->path);
            $photo->save();
        });
    }
}
