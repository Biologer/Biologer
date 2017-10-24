<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class FieldObservation extends Model
{
    use Concerns\HasDynamicFields;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['observation'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'dynamic_fields' => 'collection',
    ];

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

    public function scopeCreatedBy($query, User $user)
    {
        return $query->whereHas('observation', function ($q) use ($user) {
            return $q->createdBy($user);
        });
    }

    /**
     * Add photos to the observation, using photos' paths.
     *
     * @param  array $photos Paths
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
     * @param  array $photos
     * @return void
     */
    public function syncPhotos($photos)
    {
        $current = $this->photos()->get();

        $current->whereNotIn('path', $photos)->each->delete();

        $this->addPhotos(array_diff($photos, $current->pluck('path')->all()));
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
            'altitude' => $this->observation->altitude,
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
