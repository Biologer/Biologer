<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupTaxonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $firstSpeciesId = $this->isSpecies()
            ? $this->id
            : optional($this->descendants->first(function ($descendant) {
                return $descendant->isSpecies();
            }))->id;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'first_species_id' => $firstSpeciesId,
        ];
    }
}
