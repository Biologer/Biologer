<?php

namespace App;

use App\Filters\Filterable;
use App\Concerns\CanMemoize;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class FieldObservation extends Model
{
    use CanMemoize, Eloquence, Filterable, Mappable;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'unidentifiable' => false,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'found_dead' => 'boolean',
        'license' => 'integer',
        'unidentifiable' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'approved_at',
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
            'id' => \App\Filters\Id::class,
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
        return $this->belongsToMany(Photo::class);
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
            collect($photos)->map(function ($name) {
                return 'uploads/'.auth()->user()->id.'/'.$name;
            })->filter(function ($path) {
                return Storage::disk('public')->exists($path);
            })->map(function ($path) use ($license) {
                return Photo::store($path, [
                    'author' => $this->observation->observer,
                    'license' => $license,
                ]);
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
        $current = $this->photos()->get();

        $current->whereNotIn('path', $photos->pluck('path'))->each->delete();

        $this->addPhotos($photos->pluck('path')->diff($current->pluck('path')), $license);
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
     * Check if field observation is approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->observation->isApproved();
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
            'photos' => $this->photos,
            'observer' => $this->observation->observer,
            'identifier' => $this->observation->identifier,
            'license' => $this->license,
            'sex' => $this->observation->sex,
            'stage_id' => $this->observation->stage_id,
            'number' => $this->observation->number,
            'note' => $this->observation->note,
            'found_dead' => $this->found_dead,
            'found_dead_note' => $this->found_dead_note,
            'data_license' => $this->license,
            'time' => $this->time ? $this->time->format('H:i') : null,
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
        });
    }
}
