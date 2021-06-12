<?php

namespace App\Importing;

use App\DEM\Reader as DEMReader;
use App\Import;
use App\LiteratureObservation;
use App\LiteratureObservationIdentificationValidity;
use App\Observation;
use App\Rules\Day;
use App\Rules\Decimal;
use App\Rules\Month;
use App\Sex;
use App\Stage;
use App\Support\Dataset;
use App\Taxon;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LiteratureObservationImport extends BaseImport
{
    /**
     * @var \App\DEM\Reader
     */
    protected $demReader;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $stages;

    /**
     * Create new importer instance.
     *
     * @param  \App\Import  $import
     * @param  \App\DEM\Reader  $demReader
     * @return void
     */
    public function __construct($import, DEMReader $demReader)
    {
        parent::__construct($import);

        $this->setDEMReader($demReader);
    }

    /**
     * Set DEM reader instance to get missing elevation.
     *
     * @param  \App\DEM\Reader  $demReader
     * @return self
     */
    public function setDEMReader(DEMReader $demReader)
    {
        $this->demReader = $demReader;

        return $this;
    }

    /**
     * Definition of all calumns with their labels.
     *
     * @param  \App\User|null  $user
     * @return \Illuminate\Support\Collection
     */
    public static function columns($user = null)
    {
        return collect([
            [
                'label' => __('labels.literature_observations.taxon'),
                'value' => 'taxon',
                'required' => true,
            ],
            [
                'label' => __('labels.literature_observations.latitude'),
                'value' => 'latitude',
                'required' => true,
            ],
            [
                'label' => __('labels.literature_observations.longitude'),
                'value' => 'longitude',
                'required' => true,
            ],
            [
                'label' => __('labels.literature_observations.original_identification'),
                'value' => 'original_identification',
                'required' => true,
            ],
            [
                'label' => __('labels.literature_observations.original_identification_validity'),
                'value' => 'original_identification_validity',
                'required' => true,
            ],
            [
                'label' => __('labels.literature_observations.year'),
                'value' => 'year',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.month'),
                'value' => 'month',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.day'),
                'value' => 'day',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.elevation'),
                'value' => 'elevation',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.accuracy'),
                'value' => 'accuracy',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.location'),
                'value' => 'location',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.time'),
                'value' => 'time',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.note'),
                'value' => 'note',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.observer'),
                'value' => 'observer',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.identifier'),
                'value' => 'identifier',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.sex'),
                'value' => 'sex',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.number'),
                'value' => 'number',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.project'),
                'value' => 'project',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.habitat'),
                'value' => 'habitat',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.found_on'),
                'value' => 'found_on',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.stage'),
                'value' => 'stage',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.original_date'),
                'value' => 'original_date',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.original_locality'),
                'value' => 'original_locality',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.original_elevation'),
                'value' => 'original_elevation',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.original_coordinates'),
                'value' => 'original_coordinates',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.other_original_data'),
                'value' => 'other_original_data',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.collecting_start_year'),
                'value' => 'collecting_start_year',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.collecting_start_month'),
                'value' => 'collecting_start_month',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.collecting_end_year'),
                'value' => 'collecting_end_year',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.collecting_end_month'),
                'value' => 'collecting_end_month',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.georeferenced_by'),
                'value' => 'georeferenced_by',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.georeferenced_date'),
                'value' => 'georeferenced_date',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.minimum_elevation'),
                'value' => 'minimum_elevation',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.maximum_elevation'),
                'value' => 'maximum_elevation',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.place_where_referenced_in_publication'),
                'value' => 'place_where_referenced_in_publication',
                'required' => false,
            ],
            [
                'label' => __('labels.literature_observations.dataset'),
                'value' => 'dataset',
                'required' => false,
            ],
        ]);
    }

    /**
     * Get validation rules specific for import type.
     *
     * @return array
     */
    public static function specificValidationRules()
    {
        return [
            'publication_id' => ['required', Rule::exists('publications', 'id')],
            'is_original_data' => ['required', 'bool'],
            'cited_publication_id' => [
                'required_if:is_original_data,false', 'nullable', Rule::exists('publications', 'id'),
            ],
        ];
    }

    /**
     * Get alternative attribute names for import type.
     *
     * @return array
     */
    public static function validationAttributes()
    {
        return [
            'publication_id' => __('labels.literature_observations.publication'),
            'is_original_data' => __('labels.literature_observations.is_original_data'),
            'cited_publication_id' => __('labels.literature_observations.cited_publication'),
            'user_id' => __('labels.imports.user'),
        ];
    }

    public function generateErrorsRoute()
    {
        return route('api.literature-observation-imports.errors', $this->model());
    }

    /**
     * Make validator instance.
     *
     * @param  array  $data
     * @return \Illuminate\Validation\Validator
     */
    protected function makeValidator(array $data)
    {
        return Validator::make($data, [
            'taxon' => ['required', Rule::exists('taxa', 'name')],
            'year' => ['bail', 'nullable', 'date_format:Y', 'before_or_equal:now'],
            'month' => ['bail', 'nullable', 'numeric', new Month(Arr::get($data, 'year'))],
            'day' => ['bail', 'nullable', 'numeric', new Day(Arr::get($data, 'year'), Arr::get($data, 'month'))],
            'latitude' => ['required', new Decimal(['min' => -90, 'max' => 90])],
            'longitude' => ['required', new Decimal(['min' => -180, 'max' => 180])],
            'elevation' => ['nullable', 'integer', 'max:10000'],
            'accuracy' => ['nullable', 'integer', 'max:500000'],
            'observer' => ['nullable', 'string'],
            'identifier' => ['nullable', 'string'],
            'stage' => ['nullable', Rule::in($this->stagesTranslatedNames())],
            'sex' => ['nullable', Rule::in(Sex::labels())],
            'number' => ['nullable', 'integer', 'min:1'],
            'time' => ['nullable', 'date_format:H:i'],
            'project' => ['nullable', 'string', 'max:191'],
            'found_on' => ['nullable', 'string', 'max:191'],
            'habitat' => ['nullable', 'string', 'max:191'],
            'note' => ['nullable', 'string'],
            'dataset' => ['nullable', 'string', 'max:255'],
            'minimum_elevation' => ['nullable', 'integer', 'max:10000', 'lte:maximum_elevation', 'lte:elevation'],
            'maximum_elevation' => ['nullable', 'integer', 'max:10000', 'gte:minimum_elevation', 'gte:elevation'],
            'original_date' => ['nullable', 'string', 'max:255'],
            'original_locality' => ['nullable', 'string', 'max:255'],
            'original_elevation' => ['nullable', 'string', 'max:255'],
            'original_coordinates' => ['nullable', 'string', 'max:255'],
            'original_identification' => ['required', 'string', 'max:255'],
            'original_identification_validity' => ['required', Rule::in(LiteratureObservationIdentificationValidity::options()->values())],
            'other_original_data' => ['nullable', 'string'],
            'collecting_start_year' => ['nullable', 'integer'],
            'collecting_start_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'collecting_end_year' => ['nullable', 'integer'],
            'collecting_end_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'georeferenced_by' => ['nullable', 'string', 'max:255'],
            'georeferenced_date' => ['nullable', 'date', 'max:255'],
            'place_where_referenced_in_publication' => ['nullable', 'string', 'max:255'],
        ], [
            'year.date_format' => __('validation.year'),
            'original_identification_validity.in' => __('validation.in_extended', [
                'attribute' => __('labels.literature_observations.original_identification_validity'),
                'options' => LiteratureObservationIdentificationValidity::options()->implode(', '),
            ]),
            'sex.in' => __('validation.in_extended', [
                'attribute' => __('labels.literature_observations.sex'),
                'options' => Sex::labels()->implode(', '),
            ]),
            'stage.in' => __('validation.in_extended', [
                'attribute' => __('labels.literature_observations.stage'),
                'options' => $this->stagesTranslatedNames()->implode(', '),
            ]),
        ], [
            'taxon' => __('labels.literature_observations.taxon'),
            'year' => __('labels.literature_observations.year'),
            'month' => __('labels.literature_observations.month'),
            'day' => __('labels.literature_observations.day'),
            'latitude' => __('labels.literature_observations.latitude'),
            'longitude' => __('labels.literature_observations.longitude'),
            'elevation' => __('labels.literature_observations.elevation'),
            'accuracy' => __('labels.literature_observations.accuracy'),
            'observer' => __('labels.literature_observations.observer'),
            'identifier' => __('labels.literature_observations.identifier'),
            'stage' => __('labels.literature_observations.stage'),
            'sex' => __('labels.literature_observations.sex'),
            'number' => __('labels.literature_observations.number'),
            'time' => __('labels.literature_observations.time'),
            'project' => __('labels.literature_observations.project'),
            'habitat' => __('labels.literature_observations.habitat'),
            'found_on' => __('labels.literature_observations.found_on'),
            'note' => __('labels.literature_observations.note'),
            'original_identification' => __('labels.literature_observations.original_identification'),
            'original_date' => __('labels.literature_observations.original_date'),
            'original_locality' => __('labels.literature_observations.original_locality'),
            'original_elevation' => __('labels.literature_observations.original_elevation'),
            'original_coordinates' => __('labels.literature_observations.original_coordinates'),
            'original_identification_validity' => __('labels.literature_observations.original_identification_validity'),
            'other_original_data' => __('labels.literature_observations.other_original_data'),
            'collecting_start_year' => __('labels.literature_observations.collecting_start_year'),
            'collecting_start_month' => __('labels.literature_observations.collecting_start_month'),
            'collecting_end_year' => __('labels.literature_observations.collecting_end_year'),
            'collecting_end_month' => __('labels.literature_observations.collecting_end_month'),
            'georeferenced_by' => __('labels.literature_observations.georeferenced_by'),
            'georeferenced_date' => __('labels.literature_observations.georeferenced_date'),
            'minimum_elevation' => __('labels.literature_observations.minimum_elevation'),
            'maximum_elevation' => __('labels.literature_observations.maximum_elevation'),
            'place_where_referenced_in_publication' => __('labels.literature_observations.place_where_referenced_in_publication'),
            'dataset' => __('labels.literature_observations.dataset'),
        ]);
    }

    /**
     * Store data from single CSV row.
     *
     * @param  array  $item
     * @return void
     */
    protected function storeSingleItem(array $item)
    {
        $literatureObservation = LiteratureObservation::create(
            $this->getSpecificObservationData($item)
        );

        $literatureObservation->observation()->save(
            new Observation($this->getGeneralObservationData($item))
        );

        activity()->performedOn($literatureObservation)
            ->causedBy($this->model()->user)
            ->log('created');
    }

    /**
     * Get observation data specific to field observation from the request.
     *
     * @param  array  $item
     * @return array
     */
    protected function getSpecificObservationData(array $item)
    {
        return [
            'time' => Arr::get($item, 'time') ?: null,
            'original_date' => Arr::get($item, 'original_date') ?: null,
            'original_locality' => Arr::get($item, 'original_locality') ?: null,
            'original_elevation' => Arr::get($item, 'original_elevation') ?: null,
            'original_coordinates' => Arr::get($item, 'original_coordinates') ?: null,
            'original_identification_validity' => $this->getOriginalIdentificationValidityValue($item),
            'other_original_data' => Arr::get($item, 'other_original_data') ?: null,
            'collecting_start_year' => Arr::get($item, 'collecting_start_year') ?: null,
            'collecting_start_month' => Arr::get($item, 'collecting_start_month') ?: null,
            'collecting_end_year' => Arr::get($item, 'collecting_end_year') ?: null,
            'collecting_end_month' => Arr::get($item, 'collecting_end_month') ?: null,
            'georeferenced_by' => Arr::get($item, 'georeferenced_by') ?: null,
            'georeferenced_date' => $this->getGeoreferencedDateValue($item),
            'minimum_elevation' => Arr::get($item, 'minimum_elevation') ?: null,
            'maximum_elevation' => Arr::get($item, 'maximum_elevation') ?: null,
            'publication_id' => $this->model()->options['publication_id'],
            'is_original_data' => $this->model()->options['is_original_data'],
            'cited_publication_id' => $this->model()->options['is_original_data'] ? null : $this->model()->options['cited_publication_id'],
            'place_where_referenced_in_publication' => Arr::get($item, 'place_where_referenced_in_publication') ?: null,
        ];
    }

    /**
     * Get general observation data from the request.
     *
     * @param  array  $item
     * @return array
     */
    protected function getGeneralObservationData(array $item)
    {
        $latitude = $this->getLatitude($item);
        $longitude = $this->getLongitude($item);
        $taxon = $this->getTaxon($item);

        return [
            'taxon_id' => $taxon ? $taxon->id : null,
            'year' => Arr::get($item, 'year') ?: null,
            'month' => Arr::get($item, 'month') ?: null,
            'day' => Arr::get($item, 'day') ?: null,
            'location' => Arr::get($item, 'location') ?: null,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'mgrs10k' => mgrs10k($latitude, $longitude),
            'accuracy' => Arr::get($item, 'accuracy') ?: null,
            'elevation' => $this->getElevation($item),
            'created_by_id' => $this->model()->for_user_id ?: $this->model()->user_id,
            'observer' => Arr::get($item, 'observer') ?: null,
            'identifier' => Arr::get($item, 'identifier') ?: null,
            'sex' => Sex::getValueFromLabel(Arr::get($item, 'sex', '')),
            'number' => Arr::get($item, 'number') ?: null,
            'note' => Arr::get($item, 'note') ?: null,
            'project' => Arr::get($item, 'project') ?: null,
            'habitat' => Arr::get($item, 'habitat') ?: null,
            'found_on' => Arr::get($item, 'found_on') ?: null,
            'stage_id' => $this->getStageId($item),
            'original_identification' => Arr::get($item, 'original_identification', Arr::get($item, 'taxon')),
            'dataset' => Arr::get($item, 'dataset') ?: Dataset::default(),
            'approved_at' => now(),
        ];
    }

    /**
     * Get ID of taxon using it's name.
     *
     * @param  array  $data
     * @return \App\Taxon|null
     */
    protected function getTaxon(array $data)
    {
        return Taxon::findByName(Arr::get($data, 'taxon'));
    }

    /**
     * Get latitude.
     *
     * @param  array  $data
     * @return float
     */
    protected function getLatitude(array $data)
    {
        return (float) str_replace(',', '.', Arr::get($data, 'latitude'));
    }

    /**
     * Get longitude.
     *
     * @param  array  $data
     * @return float
     */
    protected function getLongitude(array $data)
    {
        return (float) str_replace(',', '.', Arr::get($data, 'longitude'));
    }

    /**
     * Get elevation.
     *
     * @param  array  $data
     * @return |int|null
     */
    protected function getElevation(array $data)
    {
        $elevation = Arr::get($data, 'elevation');

        if (is_numeric($elevation)) {
            return $elevation;
        }

        if ($this->demReader) {
            return $this->demReader->getElevation(
                $this->getLatitude($data),
                $this->getLongitude($data)
            );
        }
    }

    /**
     * Get all the stages.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function stages()
    {
        if (! $this->stages) {
            $this->stages = Stage::all();
        }

        return $this->stages;
    }

    /**
     * Get correctly translated stages' names.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function stagesTranslatedNames()
    {
        return $this->stages()->pluck('name_translation');
    }

    /**
     * Get stage ID.
     *
     * @param  array  $data
     * @return int|null
     */
    protected function getStageId(array $data)
    {
        $translation = strtolower(Arr::get($data, 'stage', ''));

        $stage = $this->stages()->first(function ($stage) use ($translation) {
            return strtolower($stage->name_translation) === $translation;
        });

        return $stage ? $stage->id : null;
    }

    /**
     * Get original identification validity value (from translated).
     *
     * @param array $data
     * @return int
     */
    protected function getOriginalIdentificationValidityValue(array $data)
    {
        return LiteratureObservationIdentificationValidity::options()
            ->flip()
            ->get(Arr::get($data, 'original_identification_validity'));
    }

    /**
     * Get georeferenced date value.
     *
     * @param array $data
     * @return string|null
     */
    protected function getGeoreferencedDateValue(array $data)
    {
        $value = $data['georeferenced_date'] ?? null;

        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    /**
     * Create new import using data from request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Import
     */
    protected static function createFromRequest($request)
    {
        return Import::create([
            'type' => static::class,
            'columns' => $request->input('columns', []),
            'path' => $request->file('file')->store('imports'),
            'user_id' => $request->user()->id,
            'for_user_id' => $request->input('user_id'),
            'lang' => app()->getLocale(),
            'has_heading' => (bool) $request->input('has_heading', false),
            'options' => [
                'publication_id' => $request->input('publication_id'),
                'is_original_data' => (bool) $request->input('is_original_data'),
                'cited_publication_id' => $request->input('cited_publication_id'),
            ],
        ]);
    }
}
