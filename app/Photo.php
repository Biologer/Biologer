<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    /**
     * Accessor for url.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->attribute['url'] ?: Storage::disk('public')->url($this->path);
    }

    /**
     * Store photo and save path and url.
     *
     * @param  string  $path
     * @param  array  $data
     * @param  bool  $withUrl If the url should be stored
     * @return self
     */
    public static function store($path, $data, $withUrl = false)
    {
        $photo = static::create($data);

        $photo->path = "photos/{$photo->id}/".basename($path);
        Storage::disk('public')->move($path, $photo->path);

        if ($withUrl) {
            $photo->url = Storage::disk('public')->url($this->path);
        }

        $photo->save();

        return $photo;
    }

    /**
     * Remove related file.
     *
     * @return void
     */
    public function removeFile()
    {
        return Storage::disk('public')->delete($this->path);
    }
}
