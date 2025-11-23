<?php

namespace App\Http\Resources;

use App\Models\ObservationTypeTranslation;
use Illuminate\Http\Resources\Json\JsonResource;

class ObservationTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'translations' => $this->translations->map(function (ObservationTypeTranslation $translation) {
                return [
                    'id' => $translation->id,
                    'locale' => $translation->locale,
                    'name' => $translation->name,
                ];
            }),
        ];
    }
}
