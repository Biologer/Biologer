<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransectVisitResource extends JsonResource
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

        $transectVisit = parent::toArray($request);

        $transectVisit['view_group'] = [
            'id' => $this->viewGroup->id,
            'name' => $translation && $translation->name
                ? $translation->name
                : $this->viewGroup->name,
        ];

        $transectVisit['field_observations'] = FieldObservationResource::collection($this->whenLoaded('fieldObservations'));

        return $transectVisit;
    }
}
