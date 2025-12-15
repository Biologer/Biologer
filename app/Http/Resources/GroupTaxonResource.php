<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Taxon
 */
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
        $firstSpeciesId = null;

        if ($this->isSpeciesLike()) {
            $firstSpeciesId = $this->id;
        } elseif ($this->isSubspecies()) {
            // If it's a subspecies it's most likely that the parent is the species we're looking for.
            // There's no need to load all the ancestors for this.
            $firstSpeciesId = $this->parent_id;
        } else {
            $firstSpeciesId = optional($this->descendants->first(function ($descendant) {
                return $descendant->isSpeciesLike();
            }))->id;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'native_name' => $this->native_name,
            'first_species_id' => $firstSpeciesId,
        ];
    }
}
