<?php

namespace App\Http\Requests;

use App\TransectCountObservation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreTransectCountObservation extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['string'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string'],
            'length' => ['nullable', 'string'],
            'primary_habitat' => ['nullable', 'string'],
        ];
    }

    /**
     * Store transect count observation and related data.
     *
     * @return \App\TransectCountObservation
     */
    public function store()
    {
        return DB::transaction(function () {
            return tap($this->createTransectCountObservation(), function ($transectCountObservation) {
                $this->logActivity($transectCountObservation);
            });
        });
    }

    /**
     * Create transect count observation.
     *
     * @return \App\TransectCountObservation
     */
    protected function createTransectCountObservation()
    {
        $transectCountObservation = TransectCountObservation::create($this->getTransectCountData());

        return $transectCountObservation;
    }

    /**
     * Get transect count observation data specific from the request.
     *
     * @return array
     */
    protected function getTransectCountData()
    {
        return [
            'name' => $this->input('name'),
            'description' => $this->input('description'),
            'location' => $this->input('location'),
            'length' => $this->input('length'),
            'primary_habitat' => $this->input('primary_habitat'),
        ];
    }

    /**
     * Log created activity for transect count observation.
     *
     * @param  \App\TransectCountObservation  $transectCountObservation
     * @return void
     */
    protected function logActivity(TransectCountObservation $transectCountObservation)
    {
        activity()->performedOn($transectCountObservation)
            ->causedBy($this->user())
            ->log('created');
    }

}
