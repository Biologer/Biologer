<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PublicationAttachment extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['url'];

    /**
     * Relation to the publication.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publication()
    {
        return $this->hasOne(Publication::class, 'attachment_id');
    }

    /**
     * Get the URL of the attached file.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return cache()->remember("publicationAttachment:{$this->id}:url", now()->addDay(), function () {
            return $this->filesystem()->url($this->path);
        });
    }

    /**
     * Create attachment using uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return self
     */
    public static function createFromUploadedFile(UploadedFile $file)
    {
        return static::create([
            'path' => $file->store('publication-attachments', 'public'),
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    /**
     * Delete attachments that are not connected to any publication.
     *
     * @return int
     */
    public static function deleteOldUnattached()
    {
        return static::whereDoesntHave('publication')
            ->whereDate('created_at', '<', now())
            ->chunk(500, function ($attachments) {
                foreach ($attachments as $attachment) {
                    $attachment->delete();
                }
            });
    }

    /**
     * Get filesystem instance.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function filesystem()
    {
        return Storage::disk('public');
    }

    /**
     * Delete relevant file.
     *
     * @return void
     */
    protected function deleteFile()
    {
        $this->filesystem()->delete($this->path);
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
            $model->deleteFile();
        });
    }
}
