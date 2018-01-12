<?php

namespace App;

use App\Filters\Filterable;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Support\Facades\Storage;

class FieldObservation extends Model
{
    use Concerns\HasDynamicFields, Eloquence, Filterable, Mappable;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'unidentifiable' => false,
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['observation.taxon'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'license' => 'integer',
        'unidentifiable' => 'boolean',
        'found_dead' => 'boolean',
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
      'taxon_name' => 'observation.taxon.name',
      'latitude' => 'observation.latitude',
      'longitude' => 'observation.longitude',
      'observer' => 'observation.observer',
      'year' => 'observation.year',
      'month' => 'observation.month',
      'day' => 'observation.day',
    ];

    protected function filters() {
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
     * Get observations created by given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIdentifiable($query)
    {
        return $query->where('unidentifiable', false);
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
        return $query->whereHas('observation', function($observation) use ($user) {
            return $observation->taxonCuratedBy($user);
        });
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

        $current->whereNotIn('path', $photos)->each->delete();

        $this->addPhotos(array_diff($photos, $current->pluck('path')->all()), $license);
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
        return $this->taxon ? $this->taxon->isCuratedBy($user) : true;
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
            'photos' => $this->photos->map(function ($photo) {
                return $photo->url;
            }),
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
        ];
    }

    /**
     * Return mapped as array with photos' path instead of URLs.
     *
     * @return array
     */
    public function toArrayForEdit()
    {
        $data = $this->toArray();

        $data['photos'] = $this->photos->map(function ($photo) {
            return $photo->path;
        });

        return $data;
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
}
