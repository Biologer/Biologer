<?php

namespace App\Jobs;

use App\Photo;
use Illuminate\Bus\Queueable;
use Intervention\Image\Facades\Image;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ResizeUploadedPhoto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Photo
     */
    public $photo;

    /**
     * Create a new job instance.
     *
     * @param  \App\Photo  $photo
     * @return void
     */
    public function __construct(Photo $photo)
    {
        $this->photo = $photo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $image = Image::make(Storage::disk('public')->get($this->photo->path));

        if (! $this->shouldResize($image)) {
            return;
        }

        Storage::disk('public')->put(
            $this->photo->path,
            (string) $this->resize($image)->encode()
        );
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
