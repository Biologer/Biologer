<?php

namespace App\Http\Forms;

use App\Rules\Day;
use App\Observation;
use App\Rules\Month;
use App\FieldObservation;
use App\DynamicFields\DynamicField;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\DynamicField as DynamicFieldValidation;

class FieldObservationUpdateForm extends FormRequest
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
             'longitude'=> 'required|numeric|between:-180,180',
             'altitude'=> 'required|numeric',
             'accuracy' => 'required|numeric',
             'source' => 'nullable|string',
             'photos' => [
                 'nullable',
                 'array',
                 'max:'.config('alciphron.photos_per_observation'),
             ],
             'dynamic_fields' => 'nullable|array',
             'dynamic_fields.*' => [
                 'nullable',
                 new DynamicFieldValidation(FieldObservation::dynamicFields())
             ],
         ];
     }

    /**
     * Store observation and related data.
     *
     * @return \App\Observation
     */
    public function save($observation)
    {
        return tap($this->updateObservation($observation), function ($observation) {
            $observation->syncPhotos($this->input('photos', []));
        });
    }

    /**
     * Create observation.
     *
     * @return \App\Observation
     */
    protected function updateObservation($fieldObservation)
    {
        return tap($fieldObservation, function ($observation) {
            $observation->update([
                'source' => $this->input('source') ?: auth()->user()->full_name,
                'taxon_suggestion' => $this->input('taxon_suggestion', null),
                'dynamic_fields' => $this->input('dynamic_fields', []),
            ]);

            $observation->observation()->update([
                'taxon_id' => $this->input('taxon_id'),
                'year' => $this->input('year'),
                'month' => $this->input('month', null),
                'day' => $this->input('day', null),
                'location' => $this->input('location'),
                'latitude' => $this->input('latitude'),
                'longitude' => $this->input('longitude'),
                'mgrs10k' => mgrs10k($this->input('latitude'), $this->input('longitude')),
                'accuracy' => $this->input('accuracy'),
                'altitude' => $this->input('altitude'),
            ]);
        });
    }
}
