<?php

namespace App;

use App\Filters\Filterable;
use App\Concerns\CanMemoize;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Activity;

class FieldObservation extends Model
{
    use CanMemoize, Eloquence, Filterable, Mappable;

    const STATUS_APPROVED = 'approved';
    const STATUS_PENDING = 'pending';
    const STATUS_UNIDENTIFIABLE = 'unidentifiable';

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'unidentifiable' => false,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['status'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'found_dead' => 'boolean',
        'license' => 'integer',
        'unidentifiable' => 'boolean',
        'approved_at' => 'datetime',
    ];

    protected $maps = [
        'day' => 'observation.day',
        'month' => 'observation.month',
        'latitude' => 'observation.latitude',
        'longitude' => 'observation.longitude',
        'observer' => 'observation.observer',
        'taxon_name' => 'observation.taxon.name',
        'year' => 'observation.year',
    ];

    protected function filters()
    {
        return [
            'id' => \App\Filters\Ids::class,
            'taxon' => \App\Filters\FieldObservation\Taxon::class,
            'year' => \App\Filters\Attribute::class,
            'month' => \App\Filters\Attribute::class,
            'day' => \App\Filters\Attribute::class,
            'status' => \App\Filters\FieldObservation\Status::class,
            'photos' => \App\Filters\FieldObservation\Photos::class,
            'observer' => \App\Filters\FieldObservation\Observer::class,
            'sort_by' => \App\Filters\SortBy::class,
        ];
    }

    /**
     * Main observation data.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function observation()
    {
        return $this->morphOne(Observation::class, 'details');
    }

    /**
     * Comments related to observation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->observation->comments();
    }

    /**
     * Photos of the observation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function photos()
    {
        return $this->observation->photos();
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function observedBy()
    {
        return $this->belongsTo(User::class);
    }

    public function identifiedBy()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope the query to get identifiable observations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIdentifiable($query)
    {
        return $query->where('unidentifiable', false);
    }

    /**
     * Scope the query to get unidentifiable observations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnidentifiable($query)
    {
        return $query->where('unidentifiable', true);
    }

    /**
     * Get observations created by given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\User  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedBy($query, User $user)
    {
        return $query->whereHas('observation', function ($q) use ($user) {
            return $q->createdBy($user);
        });
    }

    /**
     * Get approved observations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->whereHas('observation', function ($q) {
            return $q->approved();
        });
    }

    /**
     * Get unapproved observations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnapproved($query)
    {
        return $query->whereHas('observation', function ($q) {
            return $q->unapproved();
        });
    }

    /**
     * Get unapproved observations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->identifiable()->unapproved();
    }

    /**
     * Get only approvable observations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApprovable($query)
    {
        return $query->whereHas('observation', function ($query) {
            return $query->unapproved()->whereHas('taxon', function ($query) {
                return $query->speciesOrLower();
            });
        });
    }

    /**
     * Get only observations of taxa curated by given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\User  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCuratedBy($query, User $user)
    {
        return $query->whereHas('observation', function ($observation) use ($user) {
            return $observation->taxonCuratedBy($user);
        });
    }

    /**
     * Getter for time attribute.
     * @param string $value
     * @return \Illuminate\Support\Carbon|null
     */
    public function getTimeAttribute($value)
    {
        return $this->memoize('time', function () use ($value) {
            return $value ? Carbon::parse($value) : null;
        });
    }

    /**
     * Setter for time attribute.
     *
     * @param string $value
     */
    public function setTimeAttribute($value)
    {
        $this->forgetMemoized('time')->attributes['time'] = $value;
    }

    public function getObserverAttribute($value)
    {
        return optional($this->observedBy)->full_name ?: $this->observation->observer;
    }

    public function getIdentifierAttribute($value)
    {
        return optional($this->identifiedBy)->full_name ?: $this->observation->identifier;
    }

    /**
     * Rank translation.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        if ($this->unidentifiable) {
            return static::STATUS_UNIDENTIFIABLE;
        }

        if ($this->isApproved()) {
            return static::STATUS_APPROVED;
        }

        return static::STATUS_PENDING;
    }

    public function getStatusTranslationAttribute()
    {
        return trans('labels.field_observations.statuses.'.$this->status);
    }

    /**
     * Get translated license name.
     *
     * @return string
     */
    public function getLicenseTranslationAttribute()
    {
        return trans('licenses.'.License::findById($this->license)->name);
    }

    /**
     * Add photos to the observation, using photos' paths.
     *
     * @param  array  $photos Paths
     * @param  int  $license
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function addPhotos($photos, $license)
    {
        return $this->photos()->saveMany(
            collect($photos)->filter(function ($photo) {
                return UploadedPhoto::exists($photo['path']);
            })->map(function ($photo) use ($license) {
                return Photo::store(UploadedPhoto::relativePath($photo['path']), [
                    'author' => $this->observation->observer,
                    'license' => $license,
                    // 'metadata' => ['exif' => UploadedPhoto::formatedExif($photo['path'])], // TODO: This needs better solution.
                ], array_get($photo, 'crop'));
            })
        );
    }

    /**
     * Remove unused photos and and add new ones.
     *
     * @param  array  $photos
     * @param  int  $license
     * @return void
     */
    public function syncPhotos($photos, $license)
    {
        $result = [
            'cropped' => [],
            'added' => [],
            'removed' => [],
        ];

        $current = $this->photos()->get();

        // Removing
        $current->whereNotIn('id', $photos->pluck('id'))->each(function ($photo) use (&$result) {
            $result['removed'][] = $photo;
            $photo->delete();
        });

        // Cropping old
        $old = $current->pluck('id')->intersect($photos->pluck('id'));

        $current->whereIn('id', $old)->each(function ($photo) use ($photos, &$result) {
            $crop = array_get($photos->where('path', $photo->path)->first(), 'crop');

            if ($crop) {
                $photo->crop($crop['width'], $crop['height'], $crop['x'], $crop['y']);
                $result['cropped'][] = $photo;
            }
        });

        // Adding new
        $new = $photos->filter(function ($photo) {
            return empty(array_get($photo, 'id'));
        });
        $result['added'] = $this->addPhotos($new, $license)->all();

        return $result;
    }

    /**
     * Approve field observation.
     *
     * @return $this
     */
    public function approve()
    {
        $this->observation->approve();

        if ($this->unidentifiable) {
            $this->forceFill(['unidentifiable' => false])->save();
        }

        return $this;
    }

    /**
     * Mark observation as unidentifiable.
     *
     * @return $this
     */
    public function markAsUnidentifiable()
    {
        $this->observation->unapprove();

        if (! $this->unidentifiable) {
            $this->forceFill(['unidentifiable' => true])->save();
        }

        return $this;
    }

    /**
     * Move observation to pending.
     *
     * @return $this
     */
    public function moveToPending()
    {
        $this->observation->unapprove();

        if ($this->unidentifiable) {
            $this->forceFill(['unidentifiable' => false])->save();
        }

        return $this;
    }

    /**
     * Check if field observation is approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->observation->isApproved();
    }

    /**
     * Check if field observation is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return ! $this->isApproved() && ! $this->unidentifiable;
    }

    /**
     * Check if observation is created by given user.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isCreatedBy(User $user)
    {
        return $this->observation->isCreatedBy($user);
    }

    /**
     * Check if given user should curate this observation.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function shouldBeCuratedBy(User $user)
    {
        return $this->observation->shouldBeCuratedBy($user);
    }

    /**
     * Check if the observation can be seen by others.
     *
     * @return bool
     */
    public function isAtLeastPartiallyOpenData()
    {
        return $this->license < 40;
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'taxon' => $this->observation->taxon,
            'taxon_id' => $this->observation->taxon_id,
            'taxon_suggestion' => $this->taxon_suggestion,
            'day' => $this->observation->day,
            'month' => $this->observation->month,
            'year' => $this->observation->year,
            'location' => $this->observation->location,
            'latitude' => $this->observation->latitude,
            'longitude' => $this->observation->longitude,
            'mgrs10k' => $this->observation->mgrs10k,
            'accuracy' => $this->observation->accuracy,
            'elevation' => $this->observation->elevation,
            'photos' => $this->observation->photos,
            'observer' => $this->observer,
            'identifier' => $this->identifier,
            'license' => $this->license,
            'sex' => $this->observation->sex,
            'stage_id' => $this->observation->stage_id,
            'number' => $this->observation->number,
            'note' => $this->observation->note,
            'project' => $this->observation->project,
            'found_on' => $this->observation->found_on,
            'found_dead' => $this->found_dead,
            'found_dead_note' => $this->found_dead_note,
            'data_license' => $this->license,
            'time' => optional($this->time)->format('H:i'),
            'status' => $this->status,
            'activity' => $this->activity,
            'types' => $this->observation->types,
            'observed_by_id' => $this->observed_by_id,
            'observed_by' => $this->observedBy,
            'identified_by_id' => $this->identified_by_id,
            'identified_by' => $this->identifiedBy,
        ];
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new FieldObservationCollection($models);
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
            $model->observation->delete();
            $model->activity()->delete();
        });
    }
}
