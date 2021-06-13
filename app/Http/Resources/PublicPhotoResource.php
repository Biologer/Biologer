<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class PublicPhotoResource extends JsonResource
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
            'author' => $this->author,
            'license' => $this->license()->toArray(),
            'url' => $this->public_url,
            'is_dead' => $this->whenLoaded('observations', function () {
                if (! $observation = $this->observations->first()) {
                    return false;
                }

                return $observation->details->found_dead ?? false;
            }),
            'stage' => $this->whenLoaded('observations', function () {
                if (! $observation = $this->observations->first()) {
                    return false;
                }

                return $observation->stage ? [
                    'id' => $observation->stage->id,
                    'name' => $observation->stage->name,
                ] : null;
            }),
            'observation' => $this->whenLoaded('observations', function () {
                if (! $observation = $this->observations->first()) {
                    return new MissingValue;
                }

                return [
                    'id' => $observation->details_id,
                    'type' => $observation->details_type,
                ];
            }),
        ];
    }
}
