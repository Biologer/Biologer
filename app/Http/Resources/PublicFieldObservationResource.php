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

        if ($this->shouldHideRealCoordinates()) {
            $resource['latitude'] = (float) number_format($resource['latitude'], 1);
            $resource['longitude'] = (float) number_format($resource['longitude'], 1);
            $resource['accuracy'] = 5000;
        }

        if ($this->license()->shouldntShowExactDate()) {
            $resource['month'] = null;
            $resource['day'] = null;
        }

        $resource['photos'] = PublicPhotoResource::collection($this->photos->filter->public_url);
        $resource['observed_by'] = ObscuredUserResource::make($this->observedBy);
        $resource['identified_by'] = ObscuredUserResource::make($this->identifiedBy);
        $resource['activity'] = ActivityResource::collection($this->activity);

        return $resource;
    }
}
