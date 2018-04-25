<?php

namespace App;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class Watermark
{
    /**
     * Path to the watermark image.
     *
     * @var string
     */
    protected $watermark;

    public function __construct($watermark)
    {
        $this->watermark = $watermark;
    }

    /**
     * Apply watermark to the photo.
     *
     * @param  \App\Photo  $photo
     * @return bool
     */
    public function applyTo(Photo $photo)
    {
        if (! file_exists($this->watermark)) {
            return false;
        }

        $image = Image::make($photo->getContent());
        $watermarkWidth = floor($image->width() * 0.8);
        $image->insert($this->getWatermark($watermarkWidth), 'center');

        return Storage::disk('public')->put(
            $photo->watermarkedPath(),
            (string) $image->encode()
        );
    }

    /**
     * Prepare watermark.
     *
     * @param  int  $width
     * @return \Intervention\Image\Image
     */
    protected function getWatermark($width)
    {
        return Image::make($this->watermark)->opacity(50)
            ->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
    }
}
