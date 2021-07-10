<?php

namespace App;

use App\Jobs\ProcessUploadedPhoto;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use League\Flysystem\Adapter\Local as LocalAdapter;

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
        'url',
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
        return $query->where('license', '<=', ImageLicense::PARTIALLY_OPEN);
    }

    /**
     * Get translated license name.
     *
     * @return string
     */
    public function getLicenseNameAttribute()
    {
        return $this->license()->name();
    }

    /**
     * Get photo license instance.
     *
     * @return \App\ImageLicense
     */
    public function license()
    {
        return ImageLicense::findById($this->license);
    }

    /**
     * Accessor for url.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('photos.file', $this);
    }

    /**
     * Public URL for viewing on the site.
     *
     * @return string|null
     */
    public function getPublicUrlAttribute()
    {
        if (! $this->isVisibleToPublic()) {
            return;
        }

        if (ImageLicense::PARTIALLY_OPEN === $this->license) {
            return $this->watermarkedUrl();
        }

        return $this->makeUrl($this->path);
    }

    /**
     * Make a URL for the path.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeUrl($path)
    {
        if ($this->filesystem()->getDriver()->getAdapter() instanceof LocalAdapter) {
            return $this->filesystem()->url($path);
        }

        return $this->filesystem()->temporaryUrl($path, now()->addMinutes(30));
    }

    /**
     * Check if we can show the photo in some form to the public.
     *
     * @return bool
     */
    public function isVisibleToPublic()
    {
        return ImageLicense::CLOSED !== $this->license;
    }

    /**
     * URL of watermarked photo.
     *
     * @return string|null
     */
    protected function watermarkedUrl()
    {
        $path = $this->watermarkedPath();

        if (! $this->filesystem()->exists($path)) {
            return;
        }

        return $this->makeUrl($path);
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
     * @param  array  $crop
     * @return self
     */
    public static function store($path, array $data, $crop = [])
    {
        $data['path'] = $path;

        return static::create($data)->moveToFinalPath()->queueProcessing($crop);
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

        $this->touch();
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
     * Check if watermarked image exists.
     *
     * @return bool
     */
    public function alreadyWatermarked()
    {
        return $this->filesystem()->exists($this->watermarkedPath());
    }

    /**
     * Store the watermarked photo.
     *
     * @param  string|resource  $content
     * @return bool
     */
    public function putWatermarkedContent($content)
    {
        return $this->filesystem()->put($this->watermarkedPath(), $content);
    }

    /**
     * Get the content of the image file.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->filesystem()->get($this->path);
    }

    /**
     * Get read stream.
     *
     * @return resource
     */
    public function getReadStream()
    {
        return $this->filesystem()->readStream($this->path);
    }

    /**
     * Put the content into the image file.
     *
     * @param  string  $content
     * @return void
     */
    public function putContent($content)
    {
        return $this->filesystem()->put($this->path, $content);
    }

    /**
     * Move file to final path.
     *
     * @return $this
     */
    public function moveToFinalPath()
    {
        $this->filesystem()->put(
            $path = $this->finalPath(),
            Storage::disk('public')->readStream($this->path)
        );

        Storage::disk('public')->delete($this->path);

        return tap($this)->update([
            'path' => $path,
        ]);
    }

    /**
     * Get final path of the image.
     *
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
    public function queueProcessing($crop)
    {
        ProcessUploadedPhoto::dispatch($this, $crop);

        return $this;
    }

    /**
     * Check if photo needs to be watermarked.
     *
     * @return bool
     */
    public function needsToBeWatermarked()
    {
        return ImageLicense::PARTIALLY_OPEN === $this->license;
    }

    /**
     * Get absolute path to the file.
     *
     * @return string
     */
    public function absolutePath()
    {
        return $this->filesystem()->path($this->path);
    }

    /**
     * Remove related file.
     *
     * @return void
     */
    public function removeFiles()
    {
        $this->filesystem()->delete($this->path);
        $this->filesystem()->delete($this->watermarkedPath());
    }

    /**
     * Get filesystem used for storing the photos.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function filesystem()
    {
        return Storage::disk(config('biologer.photos_disk'));
    }

    public function streamResponse(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->filesystem()->response($this->finalPath());
    }

    /**
     * Map to array containing information for gallery.
     *
     * @return array
     */
    public function forGallery()
    {
        return [
            'url' => $this->public_url,
            'caption' => "&copy; {$this->author} ({$this->license_name})",
        ];
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            $model->removeFiles();
        });
    }
}
