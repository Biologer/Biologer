<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldObservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $fieldObservation = parent::toArray($request);

        $fieldObservation['photos'] = PhotoResource::collection($this->photos);
        $fieldObservation['observed_by'] = ObscuredUserResource::make($this->observedBy);
        $fieldObservation['identified_by'] = ObscuredUserResource::make($this->identifiedBy);
        $fieldObservation['activity'] = ActivityResource::collection($this->activity);

        return $fieldObservation;
    }
}
