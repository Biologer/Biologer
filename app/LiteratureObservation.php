<?php

namespace App;

use App\Concerns\CanMemoize;
use App\Concerns\MappedSorting;
use App\Contracts\FlatArrayable;
use App\Filters\Filterable;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Activity;

class LiteratureObservation extends Model implements FlatArrayable
{
    use CanMemoize, Filterable, MappedSorting;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'georeferenced_date' => 'date',
        'is_original_data' => 'boolean',
        'minimum_elevation' => 'integer',
        'maximum_elevation' => 'integer',
        'original_identification_validity' => 'integer',
    ];

    protected function filters()
    {
        return [
            'id' => \App\Filters\Ids::class,
            'taxon' => \App\Filters\LiteratureObservation\TaxonFilter::class,
            'taxon_id' => \App\Filters\NullFilter::class,
            'include_child_taxa' => \App\Filters\NullFilter::class,
            'year' => \App\Filters\LiteratureObservation\ObservationAttributeFilter::class,
            'month' => \App\Filters\LiteratureObservation\ObservationAttributeFilter::class,
            'day' => \App\Filters\LiteratureObservation\ObservationAttributeFilter::class,
            'sort_by' => \App\Filters\SortBy::class,
            'project' => \App\Filters\LiteratureObservation\ObservationAttributeLikeFilter::class,
            'publication_id' => \App\Filters\LiteratureObservation\PublicationFilter::class,
        ];
    }

    protected function sortMap()
    {
        return [
            'day' => 'observation.day',
            'month' => 'observation.month',
            'year' => 'observation.year',
            'taxon_name' => 'observation.taxon.name',
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
            'id', 'taxon_name', 'year', 'month', 'day',
        ];
    }

    /**
     * General observation information.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function observation()
    {
        return $this->morphOne(Observation::class, 'details');
    }

    /**
     * Publication the observation comes from.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    /**
     * The original publication the observation comes from if the main publication is not original.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function citedPublication()
    {
        return $this->belongsTo(Publication::class);
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
     * Getter for time attribute.
     *
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
     * @param  string  $value
     * @return void
     */
    public function setTimeAttribute($value)
    {
        $this->forgetMemoized('time')->attributes['time'] = $value;
    }

    /**
     * Get the instance of identification validity enum.
     *
     * @return \App\LiteratureObservationIdentificationValidity
     */
    public function identificationValidity()
    {
        return $this->memoize('original_identification_validity', function () {
            return new LiteratureObservationIdentificationValidity(
                $this->original_identification_validity
            );
        });
    }

    /**
     * Setter for `original_identification_validity` attribute.
     * We need to forget identification validity object.
     *
     * @param  string  $value
     * @return void
     */
    public function setOriginalIdentificationValidityAttribute($value)
    {
        $this->forgetMemoized('original_identification_validity')->attributes['original_identification_validity'] = $value;
    }

    /**
     * Get translation for original identification.
     *
     * @return string
     */
    public function getOriginalIdentificationValidityTranslationAttribute()
    {
        return $this->identificationValidity()->translation();
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
            'publication' => $this->publication,
            'publication_id' => $this->publication_id,
            'is_original_data' => $this->is_original_data,
            'cited_publication' => $this->is_original_data ? null : $this->citedPublication,
            'cited_publication_id' => $this->is_original_data ? null : $this->cited_publication_id,
            'place_where_referenced_in_publication' => $this->place_where_referenced_in_publication,
            'original_date' => $this->original_date,
            'original_locality' => $this->original_locality,
            'original_elevation' => $this->original_elevation,
            'original_coordinates' => $this->original_coordinates,
            'original_identification' => $this->observation->original_identification,
            'original_identification_validity' => $this->original_identification_validity,
            'other_original_data' => $this->other_original_data,
        ];
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
