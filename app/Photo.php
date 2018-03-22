<?php

namespace App;

use App\Jobs\ResizeUploadedPhoto;
use Illuminate\Support\Facades\Storage;

use Intervention\Image\Facades\Image;

class Photo extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'collection',
    ];

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
     * @param  array|null  $crop
     * @return self
     */
    public static function store($path, array $data, $crop = null)
    {
        $data['path'] = $path;
        $data['metadata']['exif'] = @exif_read_data($path);

        return static::create($data)->moveToFinalPath()->queueResize($crop);
    }

    public function crop($width, $height, $x, $y)
    {
        $this->putContent(
            (string) Image::make($this->getContent())
                ->crop($width, $height, $x, $y)
                ->encode()
        );
    }

    public function getContent()
    {
        return Storage::disk('public')->get($this->path);
    }

    public function putContent($content)
    {
        return Storage::disk('public')->put($this->path, $content);
    }

    /**
     * Move file to final path.
     *
     * @return $this
     */
    public function moveToFinalPath()
    {
        $newPath = 'photos/'.$this->id.'/'.basename($this->path);

        Storage::disk('public')->move($this->path, $newPath);

        return tap($this)->update([
            'path' => $newPath,
            'url' => Storage::disk('public')->url($newPath),
        ]);
    }

    public function queueResize($crop)
    {
        if ($crop || config('biologer.photo_resize_dimension')) {
            ResizeUploadedPhoto::dispatch($this, $crop);
        }

        return $this;
    }

    public function absolutePath()
    {
        return Storage::disk('public')->path($this->path);
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

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->removeFile();
        });
    }
}
