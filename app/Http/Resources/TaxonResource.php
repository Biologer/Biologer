<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $resource = parent::toArray($request);

        // Non-existing translations only cause confusion and trouble
        $resource['translations'] = $this->translations->filter->exists->toArray();

        return $resource;
    }
}
