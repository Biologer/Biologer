<?php

namespace App;

use Intervention\Image\Alignment;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface as Image;

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
     * @param  Image|null  $image
     * @return bool
     */
    public function applyTo(Photo $photo, Image $image = null)
    {
        if (! file_exists($this->watermark)) {
            return false;
        }

        try {
            $manager = new ImageManager(new Driver());
            $image = $image ?: $manager->decodePath($photo->getContent());

            $watermark = $this->getWatermarkFor($image);

            $image->insert($watermark, 0, $this->verticalOffset($image, $watermark), Alignment::TOP, 0.5);

            $watermarkedImageContent = (string) $image->encode();

            // Clean up.
            unset($watermark);

            return $photo->putWatermarkedContent($watermarkedImageContent);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Calculate the width for watermark.
     *
     * @param  Image  $image
     * @return int
     */
    protected function calculateWatermarkWidth($image)
    {
        return floor($image->width() * 0.8);
    }

    /**
     * Get vertical offset for the watermark.
     *
     * @param Image $image
     * @param Image $watermark
     * @return int
     */
    protected function verticalOffset(Image $image, $watermark)
    {
        $heightDiff = $image->height() - $watermark->height();

        return (int) ($heightDiff * 1 / 2);
    }

    /**
     * Prepare watermark.
     *
     * @param  Image  $image
     * @return Image
     */
    protected function getWatermarkFor(Image $image)
    {
        $manager = new ImageManager(new Driver());
        $watermark = $manager->decodePath($this->watermark);

        $watermark->scale(width: $this->calculateWatermarkWidth($image));

        return $watermark;
    }
}
