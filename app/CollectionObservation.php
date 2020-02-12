<?php

namespace App;

use App\Concerns\CanMemoize;
use App\Concerns\MappedSorting;
use App\Contracts\FlatArrayable;
use App\Filters\Filterable;
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
     * Relation to the collection this specimen is part of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collection()
    {
        return $this->belongsTo(SpecimenCollection::class);
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
}
