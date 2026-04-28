<?php

namespace App\Http\Requests;

use App\SpecimenCollection;
use Illuminate\Foundation\Http\FormRequest;

class SaveSpecimenCollection extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'institution_name' => ['nullable', 'string', 'max:255'],
            'institution_code' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Save the specimen collection using validated request data.
     *
     * @param  \App\SpecimenCollection  $specimenCollection
     * @return \App\SpecimenCollection
     */
    public function save(SpecimenCollection $specimenCollection)
    {
        $specimenCollection->fill($this->validated())->save();

        return $specimenCollection;
    }
}
