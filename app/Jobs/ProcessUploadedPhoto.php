<?php

namespace App\Jobs;

use App\Photo;
use Illuminate\Bus\Queueable;
use Intervention\Image\Facades\Image;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessUploadedPhoto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Photo
     */
    public $photo;

    /**
     * @var array|null
     */
    public $crop;

    /**
     * Create a new job instance.
     *
     * @param  \App\Photo  $photo
     * @param  array|null  $crop
     * @return void
     */
    public function __construct(Photo $photo, $crop = null)
    {
        $this->photo = $photo;
        $this->crop = $crop;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $image = Image::make($this->photo->getContent());
        $shouldResize = $this->shouldResize($image);

        if ($this->crop) {
            $image = $image->crop(
                $this->crop['width'],
                $this->crop['height'],
                $this->crop['x'],
                $this->crop['y']
            );
        }

        if ($shouldResize) {
            $image = $this->resize($image);
        }

        if ($this->crop || $shouldResize) {
            $this->photo->putContent($image->encode()->getEncoded());
        }

        if ($this->photo->needsToBeWatermarked()) {
            $this->photo->watermark();
        }
    }

    /**
     * Check if image should be resized.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    protected function shouldResize($image)
    {
        return $this->isLandscape($image)
            ? $image->width() > $this->size()
            : $image->height() > $this->size();
    }

    /**
     * Check if image is landscape oriented.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    protected function isLandscape($image)
    {
        return $image->width() > $image->height();
    }

    /**
     * Get max size of greater image dimension.
     *
     * @return int
     */
    protected function size()
    {
        return config('biologer.photo_resize_dimension');
    }

    /**
     * Check if image should be resized.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    protected function resize($image)
    {
        list($width, $height) = $this->getResizeDimensions($image);

        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $image;
    }

    /**
     * Get dimensions to which the image should be resized to.
     *
     * @param  \Intervention\Image\Image  $image
     * @return array
     */
    protected function getResizeDimensions($image)
    {
        $width = $height = null;

        if ($this->isLandscape($image)) {
            $width = $this->size();
        } else {
            $height = $this->size();
        }

        return [$width, $height];
    }
}
