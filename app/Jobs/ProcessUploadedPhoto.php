<?php

namespace App\Jobs;

use App\Photo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;

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
     * @param  array  $crop
     * @return void
     */
    public function __construct(Photo $photo, $crop = [])
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
        // We need to open image from path in order to have access to EXIF
        // so we copy it to a temporary file in the local filesystem.
        $image = Image::make($tempPath = $this->copyToTemporaryPath());

        $shouldResize = $this->shouldResize($image);
        $shouldOrientate = $image->exif('Orientation') > 1;

        $image = $image->orientate();

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

        if ($this->crop || $shouldResize || $shouldOrientate) {
            $this->photo->putContent($image->encode()->getEncoded());
        }

        // Cleanup. We don't need leftover files.
        @unlink($tempPath);
        $image->destroy();
        unset($image);

        if ($this->photo->needsToBeWatermarked()) {
            $this->photo->watermark();
        }
    }

    /**
     * Copy photo to temporary path that we can use to manipulate it.
     *
     * @return string
     */
    protected function copyToTemporaryPath()
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'BiologerPhoto');

        $source = $this->photo->getReadStream();
        $destination = fopen($tempPath, 'wb+');

        stream_copy_to_stream($source, $destination);

        fclose($source);
        fclose($destination);

        return $tempPath;
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
