<?php

namespace App\Http\Forms;

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
        $observation = $this->createObservation($this->all());

        if ($comment = trim($this->input('comment'))) {
            $observation->addNewComment($comment);
        }

        $observation->details->addPhotos($this->input('photos', []));

        $observation->details->saveDynamicFields($this->input('dynamic', []));

        return $observation;
    }

    /**
     * Create observation.
     *
     * @param  array  $data
     * @return \App\Observation
     */
    protected function createObservation($data)
    {
        return FieldObservation::create([
            'source' => $data['source'],
        ])->observation()->create([
            'year' => $data['year'],
            'month' => $data['month'],
            'day' => $data['day'],
            'location' => $data['location'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'mgrs10k' => mgrs10k($data['latitude'], $data['longitude']),
            'accuracy' => $data['accuracy'],
            'altitude' => $data['altitude'],
            'created_by_id' => auth()->user()->id,
        ]);
    }
}
