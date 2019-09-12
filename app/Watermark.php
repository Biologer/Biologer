<?php

namespace App;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Intervention\Image\Facades\Image;

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

        try {
            $image = Image::make($photo->getContent());

            $watermark = $this->getWatermarkFor($image);

            $image->insert($watermark, 'top-center', null, $this->verticalOffset($image, $watermark));

            return $photo->putWatermarkedContent($image->encode()->getEncoded());
        } catch (FileNotFoundException $e) {
            return false;
        }
    }

    /**
     * Calculate the width for watermark.
     *
     * @param  \Intervention\Image\Image  $image
     * @return int
     */
    protected function calculateWatermarkWidth($image)
    {
        return floor($image->width() * 0.8);
    }

    /**
     * Get vertical offset for the watermark.
     *
     * @param  \Intervention\Image\Image  $image
     * @param  \Intervention\Image\Image  $watermark
     */
    protected function verticalOffset($image, $watermark)
    {
        $heightDiff = $image->height() - $watermark->height();

        return (int) ($heightDiff * 1 / 2);
    }

    /**
     * Prepare watermark.
     *
     * @param  \Intervention\Image\Image  $image
     * @return \Intervention\Image\Image
     */
    protected function getWatermarkFor($image)
    {
        return Image::make($this->watermark)->opacity(50)
            ->resize($this->calculateWatermarkWidth($image), null, function ($constraint) {
                $constraint->aspectRatio();
            });
    }
}
