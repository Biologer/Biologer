<?php

namespace App;

use App\Concerns\CanMemoize;
use App\Concerns\MappedSorting;
use App\Contracts\FlatArrayable;
use App\Filters\Filterable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;

class CollectionObservation extends Model implements FlatArrayable
{
    use CanMemoize, Filterable, MappedSorting;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'georeferenced_date' => 'date',
        'minimum_elevation' => 'integer',
        'maximum_elevation' => 'integer',
        'original_identification_validity' => 'integer',
    ];

    /**
     * Filter list.
     *
     * @return array
     */
    protected function filters()
    {
        return [
            'sort_by' => \App\Filters\SortBy::class,
        ];
    }

    /**
     * List of fields that field observations can be sorted by.
     *
     * @return array
     */
    public static function sortableFields()
    {
        return [
            'id', 'taxon_name', 'collection_name',
        ];
    }

    protected function sortMap()
    {
        return [
            'taxon_name' => 'observation.taxon.name',
            'collection_name' => 'collection.name',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function photos()
    {
        return $this->observation->photos();
    }

    /**
     * Relation to the collection this specimen is part of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collection()
    {
        return $this->belongsTo(SpecimenCollection::class);
    }

    public function preparations()
    {
        return $this->belongsToMany(Preparation::class);
    }

    /**
     * Activity recorded on the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    /**
     * Serialize literature observation to a flat array.
     * Mostly used for the frontend and diffing.
     *
     * @return array
     */
    public function toFlatArray()
    {
        return [
            'id' => $this->id,
            'taxon' => $this->observation->taxon,
            'taxon_id' => $this->observation->taxon_id,
            'year' => $this->observation->year,
            'month' => $this->observation->month,
            'day' => $this->observation->day,
            'elevation' => $this->observation->elevation,
            'minimum_elevation' => $this->minimum_elevation,
            'maximum_elevation' => $this->maximum_elevation,
            'latitude' => $this->observation->latitude,
            'longitude' => $this->observation->longitude,
            'mgrs10k' => $this->observation->mgrs10k,
            'location' => $this->observation->location,
            'accuracy' => $this->observation->accuracy,
            'georeferenced_by' => $this->georeferenced_by,
            'georeferenced_date' => optional($this->georeferenced_date)->toDateString(),
            'observer' => $this->observation->observer,
            'identifier' => $this->observation->identifier,
            'note' => $this->observation->note,
            'sex' => $this->observation->sex,
            'number' => $this->observation->number,
            'project' => $this->observation->project,
            'found_on' => $this->observation->found_on,
            'habitat' => $this->observation->habitat,
            'stage_id' => $this->observation->stage_id,
            'time' => optional($this->time)->format('H:i'),
            'dataset' => $this->observation->dataset,
            'photos' => $this->observation->photos,
            'collection' => $this->collection,
            'collection_id' => $this->collection_id,
            'original_date' => $this->original_date,
            'original_locality' => $this->original_locality,
            'original_elevation' => $this->original_elevation,
            'original_coordinates' => $this->original_coordinates,
            'original_identification' => $this->observation->original_identification,
            'original_identification_validity' => $this->original_identification_validity,
            'verbatim_tag' => $this->verbatim_tag,
            'collecting_start_year' => $this->collecting_start_year,
            'collecting_start_month' => $this->collecting_start_month,
            'collecting_end_year' => $this->collecting_end_year,
            'collecting_end_month' => $this->collecting_end_month,
        ];
    }

    /**
     * Get the instance of identification validity enum.
     *
     * @return \App\ObservationIdentificationValidity
     */
    public function identificationValidity()
    {
        return $this->memoize('original_identification_validity', function () {
            return new ObservationIdentificationValidity(
                $this->original_identification_validity
            );
        });
    }

    /**
     * Add photos to the observation, using photos' paths.
     *
     * @param array  $photos Paths
     * @param  int  $defaultLicense
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function addPhotos($photos, $defaultLicense)
    {
        return $this->photos()->saveMany(
            collect($photos)->filter(function ($photo) {
                return UploadedPhoto::exists($photo['path']);
            })->map(function ($photo) use ($defaultLicense) {
                return Photo::store(UploadedPhoto::relativePath($photo['path']), [
                    'author' => $this->observation->observer ?? auth()->user()->full_name,
                    'license' => empty($photo['license']) ? $defaultLicense : $photo['license'],
                ], Arr::get($photo, 'crop', []));
            })
        );
    }

    /**
     * Remove unused photos and and add new ones.
     *
     * @param  \Illuminate\Support\Collection  $photos
     * @param  int  $defaultLicense
     * @return void
     */
    public function syncPhotos(Collection $photos, $defaultLicense)
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

        $current->whereIn('id', $old)->each(function ($photo) use ($photos, &$result, $defaultLicense) {
            $updatedPhoto = $photos->where('id', $photo->id)->first();

            if (array_key_exists('license', $updatedPhoto) && $updatedPhoto['license'] != $photo->license) {
                $photo->update(['license' => $updatedPhoto['license'] ?? $defaultLicense]);
            }

            $crop = Arr::get($updatedPhoto, 'crop');

            if ($crop) {
                $photo->crop($crop['width'], $crop['height'], $crop['x'], $crop['y']);
                $result['cropped'][] = $photo;
            }
        });

        // Adding new
        $new = $photos->filter(function ($photo) {
            return empty(Arr::get($photo, 'id'));
        });

        $result['added'] = $this->addPhotos($new, $defaultLicense)->all();

        return $result;
    }
}
