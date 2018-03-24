<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UploadedPhoto
{
    /**
     * Store uploaded photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string File name only
     */
    public static function store(UploadedFile $file)
    {
        return basename($file->storeAs(
            static::relativePath(),
            str_random().'-'.$file->getClientOriginalName(),
            'public'
        ));
    }

    /**
     * Get EXIF information of the uploaded photo.
     *
     * @param  string  $filename
     * @return array|null
     */
    public static function exif($filename)
    {
        $exif = Image::make(
            Storage::disk('public')->get(static::relativePath($filename))
        )->exif();

        return empty($exif) ? null : static::formatExif($exif);
    }

    /**
     * Format EXIF information into more readable format
     *
     * TODO: Implement formating EXIF information.
     *
     * @param  array  $exif
     * @return array
     */
    protected static function formatExif($exif)
    {
        return $exif;
    }

    /**
     * Delete the uploaded photo.
     *
     * @param  string  $filename
     * @return array|bool
     */
    public static function delete($filename)
    {
        return Storage::disk('public')->delete(static::relativePath($filename));
    }

    /**
     * Check if uploaded photo exists.
     *
     * @param  string  $filename
     * @return bool
     */
    public static function exists($filename)
    {
        return Storage::disk('public')->exists(static::relativePath($filename));
    }

    /**
     * Get relative path of uploaded file.
     *
     * @param  string|null  $filename
     * @return string
     */
    public static function relativePath($filename = null)
    {
        $path = 'uploads/'.auth()->id();

        if (is_string($filename)) {
            $path .= '/'.$filename;
        }

        return $path;
    }
}
