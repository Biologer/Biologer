<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PublicFieldObservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = parent::toArray($request);

        if ($this->license()->shouldHideRealCoordinates()) {
            $resource['latitude'] = null;
            $resource['longitude'] = null;
        }

        $resource['photos'] = PublicPhotoResource::collection($this->photos->filter->public_url);

        return $resource;
    }
}
