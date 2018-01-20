<?php

namespace App\Http\Requests;

use App\Stage;
use App\License;
use App\Rules\Day;
use App\Observation;
use App\Rules\Month;
use App\FieldObservation;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldObservation extends FormRequest
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
            'taxon_id' => 'nullable|exists:taxa,id',
            'taxon_suggestion' => 'nullable|string|max:255',
            'year' => 'bail|required|date_format:Y|before_or_equal:now',
            'month' => [
                'bail',
                'nullable',
                new Month($this->input('year')),
            ],
            'day' => [
                'bail',
                'nullable',
                new Day($this->input('year'), $this->input('month')),
            ],
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'elevation' => 'required|integer|max:10000',
            'accuracy' => 'nullable|integer|max:10000',
            'observer' => 'nullable|string',
            'identifier' => 'nullable|string',
            'sex' => ['nullable', Rule::in(Observation::SEX_OPTIONS)],
            'stage_id' => ['nullable', Rule::in(Stage::pluck('id')->all())],
            'number' => 'nullable|integer|min:1',
            'found_dead' => 'nullable|boolean',
            'found_dead_note' => 'nullable',
            'data_license' => ['nullable', Rule::in(License::getIds())],
            'image_license' => ['nullable', Rule::in(License::getIds())],
            'photos' => 'nullable|array|max:'.config('biologer.photos_per_observation'),
            'photos.*.path' => 'required',
            'time' => 'nullable|date_format:H:i',
        ];
    }

    /**
     * Store observation and related data.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return \App\FieldObservation
     */
    public function save(FieldObservation $fieldObservation)
    {
        return tap($this->updateObservation($fieldObservation), function ($observation) {
            $observation->syncPhotos(
                collect($this->input('photos', [])),
                $this->input('image_license') ?: $this->user()->settings()->get('image_license')
            );
        });
    }

    /**
     * Create observation.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return \App\FieldObservation
     */
    protected function updateObservation(FieldObservation $fieldObservation)
    {
        $fieldObservation->observation->update([
            'taxon_id' => $this->input('taxon_id'),
            'year' => $this->input('year'),
            'month' => $this->input('month'),
            'day' => $this->input('day'),
            'location' => $this->input('location'),
            'latitude' => $this->input('latitude'),
            'longitude' => $this->input('longitude'),
            'mgrs10k' => mgrs10k($this->input('latitude'), $this->input('longitude')),
            'accuracy' => $this->input('accuracy'),
            'elevation' => $this->input('elevation'),
            'observer' => $this->input('observer') && $this->user()->hasAnyRole(['admin', 'curator'])
                ? $this->input('observer')
                : $this->user()->full_name,
            'identifier' => $this->input('identifier') && $this->user()->hasAnyRole(['admin', 'curator'])
                ? $this->input('identifier')
                : null,
            'sex' => $this->input('sex'),
            'number' => $this->input('number'),
            'stage_id' => $this->input('stage_id'),
        ]);

        $fieldObservation->update([
            'found_dead' => $this->input('found_dead', false),
            'found_dead_note' => $this->input('found_dead', false) ? $this->input('found_dead_note') : null,
            'license' => $this->input('data_license') ?: $this->user()->settings()->get('data_license'),
            'taxon_suggestion' => $this->input('taxon_suggestion'),
            'time' => $this->input('time'),
        ]);

        return $fieldObservation;
    }
}
