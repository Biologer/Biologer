<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimedCountObservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $locale = app()->getLocale();

        $translation = $this->viewGroup->translations
            ->firstWhere('locale', $locale);

        $timedCountObservation = parent::toArray($request);

        $timedCountObservation['view_group'] = [
            'id' => $this->viewGroup->id,
            'name' => $translation && $translation->name
                ? $translation->name
                : $this->viewGroup->name,
        ];

        $timedCountObservation['field_observations'] = FieldObservationResource::collection($this->whenLoaded('fieldObservations'));

        return $timedCountObservation;
    }
}
