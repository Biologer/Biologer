<?php

namespace App;

use App\Events\ImportStatusUpdated;
use App\Importing\ImportStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Import extends Model
{
    use HasFactory;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'columns' => '[]',
        'options' => '[]',
        'status' => ImportStatus::PROCESSING_QUEUED,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'columns' => 'array',
        'has_heading' => 'boolean',
        'options' => 'collection',
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
        if ($this->status()->validationFailed()) {
            return $this->makeImporter()->generateErrorsRoute();
        }
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
        return Storage::path($this->parsedPath());
    }

    /**
     * URL to file with parsed data.
     *
     * @return string
     */
    public function parsedUrl()
    {
        return Storage::url($this->parsedPath());
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
        return Storage::path($this->errorsPath());
    }

    /**
     * Make JSON response with validation errors.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function errorsResponse()
    {
        return Storage::response($this->errorsPath());
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
     * Cancel import.
     *
     * @return self
     */
    public function cancel()
    {
        $this->updateStatus(ImportStatus::CANCELLED);

        return $this;
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

    /**
     * Make importer instance.
     *
     * @return \App\Importing\BaseImport
     */
    public function makeImporter()
    {
        return app()->makeWith($this->type, ['import' => $this]);
    }

    /**
     * Delete import file.
     *
     * @return void
     */
    public function deleteFiles()
    {
        Storage::delete($this->path);
        Storage::delete($this->errorsPath());
        Storage::delete($this->parsedPath());
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
