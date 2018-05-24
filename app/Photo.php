<?php

namespace App;

use Illuminate\Support\Carbon;
use App\Jobs\ResizeUploadedPhoto;
use Intervention\Image\Facades\Image;
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
        'license' => 'integer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'metadata',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'public_url',
    ];

    /**
     * Observations the photo is attached to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function observations()
    {
        return $this->belongsToMany(Observation::class);
    }

    /**
     * Scope the query to get only photos older than a day.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOlderThanDay($query)
    {
        return $query->where('updated_at', '<', Carbon::yesterday());
    }

    /**
     * Scope the query to get only public photos.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->where('license', '<=', License::PARTIALLY_OPEN);
    }

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
     * Public URL for viewing on the site.
     *
     * @return string|null
     */
    public function getPublicUrlAttribute()
    {
        if (License::CLOSED === $this->license) {
            return;
        }

        if (License::PARTIALLY_OPEN === $this->license) {
            return $this->watermarkedUrl();
        }

        return $this->url;
    }

    /**
     * URL of watermarked photo.
     *
     * @return string|null
     */
    protected function watermarkedUrl()
    {
        $path = $this->watermarkedPath();

        if (! Storage::disk('public')->exists($path)) {
            return;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * File path where the watermark should be stored.
     *
     * @return string
     */
    public function watermarkedPath()
    {
        return $this->finalPath('w'.$this->filename());
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

        return static::create($data)->moveToFinalPath()->queueResize($crop);
    }

    /**
     * Crop the image.
     *
     * @param  int  $width
     * @param  int  $height
     * @param  int  $x
     * @param  int  $y
     * @return void
     */
    public function crop($width, $height, $x, $y)
    {
        $this->putContent(
            (string) Image::make($this->getContent())
                ->crop($width, $height, $x, $y)
                ->encode()
        );
    }

    /**
     * Watermark the photo.
     *
     * @return bool
     */
    public function watermark()
    {
        return app(Watermark::class)->applyTo($this);
    }

    /**
     * Get the content of the image file.
     *
     * @return string
     */
    public function getContent()
    {
        return Storage::disk('public')->get($this->path);
    }

    /**
     * Put the content into the image file.
     *
     * @param  string  $content
     * @return void
     */
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
        Storage::disk('public')->move($this->path, $path = $this->finalPath());

        return tap($this)->update([
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
        ]);
    }

    /**
     * Get final path of the image.
     * @param  string|null  $filename
     * @return string
     */
    protected function finalPath($filename = null)
    {
        return 'photos/'.$this->id.'/'.($filename ?? $this->filename());
    }

    /**
     * Get base file name.
     *
     * @return string
     */
    protected function filename()
    {
        return basename($this->path);
    }

    /**
     * Queue the resize of the photo.
     *
     * @param  array  $crop
     * @return $this
     */
    public function queueResize($crop)
    {
        if ($crop || config('biologer.photo_resize_dimension')) {
            ResizeUploadedPhoto::dispatch($this, $crop);
        }

        return $this;
    }

    /**
     * Get absolute path to the file.
     *
     * @return string
     */
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
