<?php

namespace App\Http\Requests;

use App\AtlasCode;
use App\FieldObservation;
use App\ImageLicense;
use App\License;
use App\Notifications\FieldObservationForApproval;
use App\ObservationType;
use App\Rules\Day;
use App\Rules\Decimal;
use App\Rules\Month;
use App\Sex;
use App\Stage;
use App\Support\Dataset;
use App\Taxon;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreFieldObservation extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'taxon_id' => ['nullable', 'exists:taxa,id'],
            'taxon_suggestion' => ['nullable', 'string', 'max:191'],
            'year' => ['bail', 'required', 'date_format:Y', 'before_or_equal:now'],
            'month' => [
                'bail',
                'nullable',
                'numeric',
                new Month($this->input('year')),
            ],
            'day' => [
                'bail',
                'nullable',
                'numeric',
                new Day($this->input('year'), $this->input('month')),
            ],
            'latitude' => ['required', new Decimal(['min' => -90, 'max' => 90])],
            'longitude' => ['required', new Decimal(['min' => -180, 'max' => 180])],
            'elevation' => ['required', 'integer', 'max:10000'],
            'accuracy' => ['nullable', 'integer', 'max:10000'],
            'observer' => ['nullable', 'string'],
            'identifier' => ['nullable', 'string'],
            'stage_id' => ['nullable', Rule::in(Stage::pluck('id'))],
            'sex' => ['nullable', Rule::in(Sex::options()->keys())],
            'number' => ['nullable', 'integer', 'min:1'],
            'found_dead' => ['nullable', 'boolean'],
            'found_dead_note' => ['nullable', 'string', 'max:1000'],
            'data_license' => ['nullable', Rule::in(License::ids())],
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
            'habitat' => ['nullable', 'string', 'max:191'],
            'found_on' => ['nullable', 'string', 'max:191'],
            'note' => ['nullable', 'string'],
            'observation_types_ids' => [
                'nullable', 'array', Rule::in(ObservationType::pluck('id')),
            ],
            'observed_by_id' => ['nullable', Rule::exists('users', 'id')],
            'identified_by_id' => ['nullable', Rule::exists('users', 'id')],
            'dataset' => ['nullable', 'string', 'max:255'],
            'atlas_code' => ['nullable', 'integer', Rule::in(AtlasCode::CODES)],
        ];
    }

    /**
     * Store observation and related data.
     *
     * @return \App\Observation
     */
    public function store()
    {
        return DB::transaction(function () {
            return tap($this->createObservation(), function ($fieldObservation) {
                $fieldObservation->addPhotos(
                    collect($this->input('photos', [])),
                    $this->user()->settings()->get('image_license')
                );

                $this->syncRelations($fieldObservation);

                $this->logActivity($fieldObservation);

                // $this->notifyCurators($fieldObservation);
            });
        });
    }

    /**
     * Create observation.
     *
     * @return \App\FieldObservation
     */
    protected function createObservation()
    {
        $fieldObservation = FieldObservation::create($this->getSpecificObservationData());

        $fieldObservation->observation()->create($this->getGeneralObservationData());

        return $fieldObservation;
    }

    /**
     * Get observation data specific to field observation from the request.
     *
     * @return array
     */
    protected function getSpecificObservationData()
    {
        return [
            'license' => $this->input('data_license') ?: $this->user()->settings()->get('data_license'),
            'taxon_suggestion' => $this->getTaxonName(),
            'found_dead' => $this->input('found_dead', false),
            'found_dead_note' => $this->input('found_dead', false) ? $this->input('found_dead_note') : null,
            'time' => $this->input('time'),
            'observed_by_id' => $this->getObservedBy(),
            'identified_by_id' => $this->getIdentifedBy(),
            'atlas_code' => $this->input('atlas_code'),
        ];
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
            'observer' => $this->getObserver(),
            'identifier' => $this->getIdentifier(),
            'sex' => $this->input('sex'),
            'stage_id' => $this->input('stage_id'),
            'number' => $this->input('number'),
            'note' => $this->input('note'),
            'project' => $this->input('project'),
            'habitat' => $this->input('habitat'),
            'found_on' => $this->input('found_on'),
            'original_identification' => $this->getTaxonName(),
            'dataset' => $this->input('dataset') ?? Dataset::default(),
            'client_name' => $this->getClientName(),
        ];
    }

    /**
     * Get the taxon name.
     *
     * @return string|null
     */
    protected function getTaxonName()
    {
        return $this->input('taxon_id')
            ? Taxon::find($this->input('taxon_id'))->name
            : $this->input('taxon_suggestion');
    }

    /**
     * Get ID of observer name.
     *
     * @return string|null
     */
    protected function getObservedBy()
    {
        if (! $this->user()->hasAnyRole(['admin', 'curator'])) {
            return $this->user()->id;
        }

        if ($this->input('observed_by_id')) {
            return $this->input('observed_by_id');
        }

        if (! $this->input('observer')) {
            return $this->user()->id;
        }
    }

    /**
     * Get observer name.
     *
     * @return string|null
     */
    protected function getObserver()
    {
        if ($this->getObservedBy()) {
            return User::find($this->getObservedBy())->full_name;
        }

        return $this->input('observer');
    }

    /**
     * Get identifier name.
     *
     * @return string|null
     */
    protected function getIdentifier()
    {
        if (! $this->user()->hasAnyRole(['admin', 'curator'])) {
            return $this->isIdentified() ? $this->user()->full_name : null;
        }

        return $this->input('identified_by_id')
                ? User::find($this->input('identified_by_id'))->full_name
                : $this->input('identifier');
    }

    /**
     * Get ID of identifier.
     *
     * @return int|null
     */
    protected function getIdentifedBy()
    {
        if (! $this->isIdentified()) {
            return;
        }

        if (! $this->user()->hasAnyRole(['admin', 'curator'])) {
            return $this->user()->id;
        }

        if ($this->input('identified_by_id')) {
            return $this->input('identified_by_id');
        }

        if (! $this->input('identifier')) {
            return $this->user()->id;
        }
    }

    /**
     * Check if the observation has been identified.
     *
     * @return bool
     */
    protected function isIdentified()
    {
        return $this->input('taxon_id') || $this->input('taxon_suggestion');
    }

    /**
     * Sync field observation relations.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return void
     */
    protected function syncRelations(FieldObservation $fieldObservation)
    {
        $fieldObservation->observation->types()->sync($this->input('observation_types_ids', []));
    }

    /**
     * Log created activity for field observation.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return void
     */
    protected function logActivity(FieldObservation $fieldObservation)
    {
        activity()->performedOn($fieldObservation)
            ->causedBy($this->user())
            ->log('created');
    }

    /**
     * Notify curators of new field observation.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return void
     */
    private function notifyCurators(FieldObservation $fieldObservation)
    {
        if ($fieldObservation->shouldBeCuratedBy($this->user(), false)) {
            return;
        }

        $fieldObservation->curators()->each(function ($curator) use ($fieldObservation) {
            if (! $this->user()->is($curator)) {
                $curator->notify(new FieldObservationForApproval($fieldObservation));
            }
        });
    }

    private function getClientName()
    {
        $token = $this->user()->token();

        if (! $token) {
            return;
        }

        if ($token->transient()) {
            return sprintf('%s Website', config('app.name'));
        }

        return $token->client->name;
    }
}
