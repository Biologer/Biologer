<?php

namespace App;

use App\Jobs\ResizeUploadedPhoto;
use Illuminate\Support\Facades\Storage;

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
     * @return self
     */
    public static function store($path, array $data)
    {
        $data['path'] = $path;
        $data['metadata']['exif'] = @exif_read_data($path);

        return static::create($data)->moveToFinalPath()->queueResize();
    }

    /**
     * Final path to file.
     *
     * @return string
     */
    protected function getFinalPath()
    {
        return 'photos/'.$this->id.'/'.basename($this->path);
    }

    /**
     * Move file to final path.
     *
     * @return $this
     */
    public function moveToFinalPath()
    {
        $newPath = $this->getFinalPath();

        Storage::disk('public')->move($this->path, $newPath);

        return tap($this)->update([
            'path' => $newPath,
            'url' => Storage::disk('public')->url($newPath),
        ]);
    }

    public function queueResize()
    {
        if (config('biologer.photo_resize_dimension')) {
            ResizeUploadedPhoto::dispatch($this);
        }

        return $this;
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
