<?php

namespace App;

use App\Support\Exif;
use Illuminate\Http\UploadedFile;
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
        return basename($file->store(
            static::relativePath(),
            'public'
        ));
    }

    /**
     * Get raw EXIF information from the uploaded photo.
     *
     * @param  string  $filename
     * @return array|null
     */
    public static function exif($filename)
    {
        return @exif_read_data(static::getAsDataUri($filename), 'EXIF');
    }

    /**
     * Get formated and usable EXIF information.
     * @param  string  $filename
     * @return array|null
     */
    public static function formatedExif($filename)
    {
        return (new Exif(static::exif($filename)))->format();
    }

    /**
     * Get photo file content as string.
     *
     * @param  string  $filename
     * @return string
     */
    protected static function get($filename)
    {
        return Storage::disk('public')->get(static::relativePath($filename));
    }

    /**
     * Get the photo contend as data uri.
     *
     * @param  string  $filename
     * @return string
     */
    protected static function getAsDataUri($filename)
    {
        if (! static::exists($filename)) {
            return;
        }

        $image = static::get($filename);
        $size = getimagesizefromstring($image);

        return sprintf('data:%s;base64,%s', $size['mime'], base64_encode($image));
    }

    protected static function absolutePath($filename)
    {
        return Storage::disk('public')->path(static::relativePath($filename));
    }

    protected static function url($filename)
    {
        return Storage::disk('public')->url(static::relativePath($filename));
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
