<?php

namespace App\Http\Requests;

use App\License;
use App\CollectionObservation;
use App\ImageLicense;
use App\ObservationIdentificationValidity;
use App\Rules\Day;
use App\Rules\Decimal;
use App\Rules\Month;
use App\Sex;
use App\Stage;
use App\Support\Dataset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreCollectionObservation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'taxon_id' => ['required', Rule::exists('taxa', 'id')],
            'collection_id' => ['required', Rule::exists('specimen_collections', 'id')],
            'year' => ['bail', 'nullable', 'date_format:Y', 'before_or_equal:now'],
            'month' => [
                'bail', 'nullable', 'numeric', new Month($this->input('year')),
            ],
            'day' => [
                'bail', 'nullable', 'numeric', new Day($this->input('year'), $this->input('month')),
            ],
            'latitude' => ['required', new Decimal(['min' => -90, 'max' => 90])],
            'longitude' => ['required', new Decimal(['min' => -180, 'max' => 180])],
            'elevation' => ['required', 'integer', 'max:10000'],
            'accuracy' => ['nullable', 'integer', 'max:500000'],
            'observer' => ['nullable', 'string'],
            'identifier' => ['nullable', 'string'],
            'stage_id' => ['nullable', Rule::in(Stage::pluck('id'))],
            'sex' => ['nullable', Rule::in(Sex::options()->keys())],
            'number' => ['nullable', 'integer', 'min:1'],
            'photos' => [
                'nullable',
                'array',
                'max:'.config('biologer.photos_per_observation'),
            ],
            'photos.*.crop' => ['nullable', 'array'],
            'photos.*.crop.x' => ['required_with:photos.*.crop', 'integer'],
            'photos.*.crop.y' => ['required_with:photos.*.crop', 'integer'],
            'photos.*.crop.width' => ['required_with:photos.*.crop', 'integer'],
            'photos.*.crop.height' => ['required_with:photos.*.crop', 'integer'],
            'photos.*.license' => ['nullable', Rule::in(ImageLicense::ids())],
            'time' => ['nullable', 'date_format:H:i'],
            'project' => ['nullable', 'string', 'max:191'],
            'found_on' => ['nullable', 'string', 'max:191'],
            'habitat' => ['nullable', 'string', 'max:191'],
            'note' => ['nullable', 'string'],
            'observed_by_id' => ['nullable', Rule::exists('users', 'id')],
            'identified_by_id' => ['nullable', Rule::exists('users', 'id')],
            'dataset' => ['nullable', 'string', 'max:255'],
            'data_license' => ['nullable', Rule::in(License::ids())],
            'minimum_elevation' => [
                'nullable', 'integer', 'max:10000', 'lte:maximum_elevation', 'lte:elevation',
            ],
            'maximum_elevation' => [
                'nullable', 'integer', 'max:10000', 'gte:minimum_elevation', 'gte:elevation',
            ],
            'original_date' => ['nullable', 'string', 'max:255'],
            'original_locality' => ['nullable', 'string', 'max:255'],
            'original_elevation' => ['nullable', 'string', 'max:255'],
            'original_coordinates' => ['nullable', 'string', 'max:255'],
            'original_identification' => ['required', 'string', 'max:255'],
            'original_identification_validity' => ['required', Rule::in(ObservationIdentificationValidity::values())],
            'verbatim_tag' => ['nullable', 'string'],
            'collecting_start_year' => ['nullable', 'integer'],
            'collecting_start_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'collecting_end_year' => ['nullable', 'integer'],
            'collecting_end_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'georeferenced_by' => ['nullable', 'string', 'max:255'],
            'georeferenced_date' => ['nullable', 'string', 'max:255'],
            'collector' => ['nullable', 'string', 'max:191'],
            'catalogue_number' => ['nullable', 'string', 'max:191'],
            'cabinet_number' => ['nullable', 'string', 'max:191'],
            'box_number' => ['nullable', 'string', 'max:191'],
            'disposition' => ['nullable', 'string', 'max:191'],
            'preparator' => ['nullable', 'string', 'max:191'],
            'preparation_method' => ['nullable', 'string', 'max:191'],
            'type_status' => ['nullable', 'string', 'max:191'],
        ];
    }

    public function save()
    {
        return DB::transaction(function () {
            $observation = $this->createObservation();

            $observation->addPhotos(
                collect($this->input('photos', [])),
                $this->user()->settings()->get('image_license')
            );

            $this->logActivity($observation);

            return $observation;
        });
    }

    /**
     * Create observation.
     *
     * @return \App\CollectionObservation
     */
    protected function createObservation()
    {
        $collectionObservation = CollectionObservation::create($this->getSpecificObservationData());

        $collectionObservation->observation()->create($this->getGeneralObservationData());

        return $collectionObservation;
    }

    /**
     * Get observation data specific to field observation from the request.
     *
     * @return array
     */
    protected function getSpecificObservationData()
    {
        return $this->only([
            'time',
            'original_date',
            'original_locality',
            'original_elevation',
            'original_coordinates',
            'original_identification_validity',
            'verbatim_tag',
            'collecting_start_year',
            'collecting_start_month',
            'collecting_end_year',
            'collecting_end_month',
            'georeferenced_by',
            'georeferenced_date',
            'minimum_elevation',
            'maximum_elevation',
            'collection_id',
            'collector',
            'catalogue_number',
            'cabinet_number',
            'box_number',
            'disposition',
            'preparator',
            'preparation_method',
            'type_status',
        ]);
    }

    /**
     * Get general observation data from the request.
     *
     * @return array
     */
    protected function getGeneralObservationData()
    {
        $latitude = (float) str_replace(',', '.', $this->input('latitude'));
        $longitude = (float) str_replace(',', '.', $this->input('longitude'));

        return [
            'taxon_id' => $this->input('taxon_id'),
            'year' => $this->input('year'),
            'month' => $this->input('month') ? (int) $this->input('month') : null,
            'day' => $this->input('day') ? (int) $this->input('day') : null,
            'location' => $this->input('location'),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'mgrs10k' => mgrs10k($latitude, $longitude),
            'accuracy' => $this->input('accuracy'),
            'elevation' => $this->input('elevation'),
            'created_by_id' => $this->user()->id,
            'observer' => $this->input('observer'),
            'identifier' => $this->input('identifier'),
            'sex' => $this->input('sex'),
            'stage_id' => $this->input('stage_id'),
            'number' => $this->input('number'),
            'note' => $this->input('note'),
            'project' => $this->input('project'),
            'found_on' => $this->input('found_on'),
            'habitat' => $this->input('habitat'),
            'original_identification' => $this->input('original_identification'),
            'dataset' => $this->input('dataset') ?? Dataset::default(),
            'approved_at' => now(),
        ];
    }

    /**
     * Log created activity for collection observation.
     *
     * @param  \App\CollectionObservation  $collectionObservation
     * @return void
     */
    protected function logActivity(CollectionObservation $collectionObservation)
    {
        activity()->performedOn($collectionObservation)
            ->causedBy($this->user())
            ->log('created');
    }
}
