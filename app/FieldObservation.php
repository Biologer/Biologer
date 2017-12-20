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
        'dynamic_fields' => 'collection',
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
     * Available dynamic fields.
     *
     * @return array
     */
    public static function dynamicFields()
    {
        return [
            'gender' => \App\DynamicFields\Gender::class,
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
     * Get only pending observations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->whereHas('observation', function ($q) {
            return $q->unapproved();
        });
    }

    /**
     * Get observations created by given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\User                              $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedBy($query, User $user)
    {
        return $query->whereHas('observation', function ($q) use ($user) {
            return $q->createdBy($user);
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
     * Get only approvable observation.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApprovable($query)
    {
        return $query->whereHas('observation', function ($q) {
            return $q->unapproved()->whereNotNull('taxon_id');
        });
    }

    /**
     * Add photos to the observation, using photos' paths.
     *
     * @param  array  $photos Paths
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function addPhotos($photos)
    {
        return $this->photos()->saveMany(
            collect($photos)->map(function ($name) {
                return 'uploads/'.auth()->user()->id.'/'.$name;
            })->filter(function ($path) {
                return Storage::disk('public')->exists($path);
            })->map(function ($path) {
                return Photo::store($path, [
                    'author' => $this->source,
                ]);
            })
        );
    }

    /**
     * Remove unused photos and and add new ones.
     *
     * @param  array  $photos
     * @return void
     */
    public function syncPhotos($photos)
    {
        $current = $this->photos()->get();

        $current->whereNotIn('path', $photos)->each->delete();

        $this->addPhotos(array_diff($photos, $current->pluck('path')->all()));
    }

    /**
     * Approve field observation.
     *
     * @return void
     */
    public function approve()
    {
        $this->observation->approve();
    }

    /**
     * Check if field observation is approved.
     *
     * @return void
     */
    public function isApproved()
    {
        return $this->observation->isApproved();
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
            'taxon_id' => $this->taxon_id,
            'taxon_suggestion' => $this->taxon_suggestion,
            'day' => $this->observation->day,
            'month' => $this->observation->month,
            'year' => $this->observation->year,
            'location' => $this->observation->location,
            'latitude' => $this->observation->latitude,
            'longitude' => $this->observation->longitude,
            'accuracy' => $this->observation->accuracy,
            'elevation' => $this->observation->elevation,
            'photos' => $this->photos->map(function ($photo) {
                return $photo->url;
            }),
            'source' => $this->source,
            'dynamic_fields' => $this->dynamic_fields,
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
}
