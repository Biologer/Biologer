<?php

namespace App\Http\Forms;

use App\Observation;
use App\FieldObservation;
use App\DynamicFields\DynamicField;
use Illuminate\Foundation\Http\FormRequest;

class NewFieldObservationForm extends FormRequest
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
        $dynamicFields = implode(',', FieldObservation::availableDynamicFieldsNames());

        return [
            'taxon_id' => 'nullable|exists:taxa,id',
            'year' => 'required|date_format:Y|before_or_equal:now',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude'=> 'required|numeric|between:-180,180',
            'altitude'=> 'required|numeric',
            'accuracy' => 'required|numeric',
            'source' => 'nullable|string',
            'photos' => 'nullable|array|max:'.config('alciphron.photos_per_observation'),
            'dynamic.*' => 'bail|nullable|df_supported:'.$dynamicFields.'|df_valid'
        ];
    }

    /**
     * Store observation and related data.
     *
     * @return \App\Observation
     */
    public function save()
    {
        return tap($this->createObservation(), function ($observation) {
            if ($comment = trim($this->input('comment'))) {
                $observation->addNewComment($comment);
            }

            $observation->details->addPhotos($this->input('photos', []));
            $observation->details->saveDynamicFields($this->input('dynamic', []));
        });
    }

    /**
     * Create observation.
     *
     * @return \App\Observation
     */
    protected function createObservation()
    {
        return FieldObservation::create([
            'source' => $this->input('source', null) ?: auth()->user()->full_name,
        ])->observation()->create([
            'taxon_id' => $this->input('taxon_id', null),
            'year' => $this->input('year'),
            'month' => $this->input('month', null),
            'day' => $this->input('day', null),
            'location' => $this->input('location'),
            'latitude' => $this->input('latitude'),
            'longitude' => $this->input('longitude'),
            'mgrs10k' => mgrs10k($this->input('latitude'), $this->input('longitude')),
            'accuracy' => $this->input('accuracy'),
            'altitude' => $this->input('altitude'),
            'created_by_id' => auth()->user()->id,
        ]);
    }
}
