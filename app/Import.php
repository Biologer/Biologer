<?php

namespace App;

use App\Importing\ImportStatus;
use App\Events\ImportStatusUpdated;
use Illuminate\Support\Facades\Storage;

class Import extends Model
{
    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => ImportStatus::PROCESSING_QUEUED,
        'columns' => '[]',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'columns' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['errors_url'];

    /**
     * Relation to the user that submitted file for import.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope the query to get only imports submitted by given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\User  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSubmittedBy($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Accessor for "errors_rul" virtual attribute.
     *
     * @return string
     */
    public function getErrorsUrlAttribute()
    {
        return $this->errorsUrl();
    }

    /**
     * Absolute path to uploaded file.
     *
     * @return string
     */
    public function absolutePath()
    {
        return Storage::path($this->path);
    }

    /**
     * Relative path to file with parsed data.
     *
     * @return string
     */
    public function parsedPath()
    {
        return "imports/{$this->id}.json";
    }

    /**
     * Absolute path to file with parsed data.
     *
     * @return string
     */
    public function parsedAbsolutePath()
    {
        return Storage::disk('public')->path($this->parsedPath());
    }

    /**
     * URL to file with parsed data.
     *
     * @return string
     */
    public function parsedUrl()
    {
        return Storage::disk('public')->url($this->parsedPath());
    }

    /**
     * Relative path to file with validation errors.
     *
     * @return string
     */
    public function errorsPath()
    {
        return "imports/{$this->id}-errors.json";
    }

    /**
     * Absolute path to file with validation errors.
     *
     * @return string
     */
    public function errorsAbsolutePath()
    {
        return Storage::disk('public')->path($this->errorsPath());
    }

    /**
     * URL to file validation errors.
     *
     * @return string
     */
    public function errorsUrl()
    {
        return Storage::disk('public')->url($this->errorsPath());
    }

    /**
     * Get instance of status enum.
     *
     * @return \App\Importing\ImportStatus
     */
    public function status()
    {
        return new ImportStatus($this->status);
    }

    /**
     * Scope the query to get only imports that are in progress.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInProgress($query)
    {
        return $query->whereNotIn('status', ImportStatus::doneStatuses());
    }

    /**
     * Scope the query to get only imports that are done.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsDone($query)
    {
        return $query->whereIn('status', ImportStatus::doneStatuses());
    }

    /**
     * Update import status.
     *
     * @param  \App\Importing\ImportStatus|string  $status
     * @return void
     */
    protected function updateStatus($status)
    {
        $this->update(['status' => $status]);

        ImportStatusUpdated::dispatch($this);
    }

    /**
     * Update status to "parsing".
     *
     * @return void
     */
    public function updateStatusToParsing()
    {
        $this->updateStatus(ImportStatus::PARSING);
    }

    /**
     * Update status to "parsed".
     *
     * @return void
     */
    public function updateStatusToParsed()
    {
        $this->updateStatus(ImportStatus::PARSED);
    }

    /**
     * Update status to "validating".
     *
     * @return void
     */
    public function updateStatusToValidating()
    {
        $this->updateStatus(ImportStatus::VALIDATING);
    }

    /**
     * Update validation status.
     *
     * @param  bool  $passed
     * @return void
     */
    public function updateValidationStatus($passed)
    {
        $status = $passed
            ? ImportStatus::VALIDATION_PASSED
            : ImportStatus::VALIDATION_FAILED;

        $this->updateStatus($status);
    }

    /**
     * Update status to "saving".
     *
     * @return void
     */
    public function updateStatusToSaving()
    {
        $this->updateStatus(ImportStatus::SAVING);
    }

    /**
     * Update status to "stored".
     *
     * @return void
     */
    public function updateStatusToSaved()
    {
        $this->updateStatus(ImportStatus::SAVED);
    }

    /**
     * Update status to "saving_failed".
     *
     * @return void
     */
    public function updateStatusToSavingFailed()
    {
        $this->updateStatus(ImportStatus::SAVING_FAILED);
    }
}
