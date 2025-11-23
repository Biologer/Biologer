<?php

namespace App\Models;

use App\Events\ExportStatusUpdated;

use App\Exports\ExportStatus;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Support\Facades\Storage;

class Export extends Model implements HasLocalePreference
{
    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => ExportStatus::QUEUED,
        'with_header' => false,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'filter' => 'collection',
        'columns' => 'array',
        'with_header' => 'boolean',
    ];

    /**
     * The attributes that should be visible in serialization.
     *
     * @var array
     */
    protected $visible = ['id', 'status', 'url'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['url'];

    /**
     * User that requested export.
     *
     * @return \App\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the url to the exported file.
     *
     * @return string|null
     */
    public function getUrlAttribute()
    {
        if ($this->status !== ExportStatus::FINISHED) {
            return;
        }

        return route('export-download', $this);
    }

    /**
     * Download response for the export file.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download()
    {
        return $this->filesystem()->download($this->path(), $this->filename);
    }

    /**
     * Get starage filesystem instance.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function filesystem()
    {
        return Storage::disk('local');
    }

    /**
     * Path to the folder with files for this export.
     *
     * @return string
     */
    protected function dir()
    {
        return "exports/{$this->user_id}";
    }

    /**
     * Get relative path to the export file.
     *
     * @return string
     */
    public function path()
    {
        return "{$this->dir()}/{$this->filename}";
    }

    /**
     * Perform the export.
     *
     * @return void
     */
    public function perform()
    {
        app($this->type)->export($this);
    }

    /**
     * Move export file from temporary to final path.
     *
     * @param  string  $tempFilePath
     * @return void
     */
    public function moveToFinalPath($tempFilePath)
    {
        $tempFile = fopen($tempFilePath, 'r');

        $this->filesystem()->put($this->path(), $tempFile);

        fclose($tempFile);
        @unlink($tempFilePath);
    }

    /**
     * Update status to exporting.
     *
     * @return self
     */
    public function updateStatusToExporting()
    {
        return $this->updateStatus(ExportStatus::EXPORTING);
    }

    /**
     * Update status to failed.
     *
     * @return self
     */
    public function updateStatusToFailed()
    {
        return $this->updateStatus(ExportStatus::FAILED);
    }

    /**
     * Update status to finished.
     *
     * @return self
     */
    public function updateStatusToFinished()
    {
        return $this->updateStatus(ExportStatus::FINISHED);
    }

    /**
     * Update export status.
     *
     * @param  string  $status
     * @return self
     */
    protected function updateStatus($status)
    {
        $this->update(['status' => $status]);

        ExportStatusUpdated::dispatch($this);

        return $this;
    }

    /**
     * Check if export is in progress.
     *
     * @return bool
     */
    public function isInProgress()
    {
        return in_array($this->status, ExportStatus::inProgress());
    }

    /**
     * Get the preferred locale of the entity.
     *
     * @return string|null
     */
    public function preferredLocale()
    {
        return $this->locale;
    }

    /**
     * Delete exported files and dirs.
     *
     * @return void
     */
    public function deleteFiles()
    {
        $this->filesystem()->deleteDirectory($this->dir());
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
            $model->deleteFiles();
        });
    }
}
