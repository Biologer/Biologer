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
        $user = auth('api')->user();

        // Check if the user has privileged acces to this field observation
        $isOwner = $user && $user->id === $this->observed_by_id;
        $isAdmin = $user && $user->hasRole('admin');
        $isCurator = $user && $this->shouldBeCuratedBy($user);
        $isPrivileged = $isOwner || $isAdmin || $isCurator;

        // Limit access if the user has no full access rights
        if (!$isPrivileged) {
            if ($this->shouldHideRealCoordinates()) {
                $resource['latitude'] = (float) number_format($this->observation->latitude, 1);
                $resource['longitude'] = (float) number_format($this->observation->longitude, 1);
                $resource['accuracy'] = 5000;
            }

            if ($this->license()->shouldntShowExactDate()) {
                $resource['month'] = null;
                $resource['day'] = null;
            }
        } else {
            // Privileged users shoud get the full data
            $resource['latitude'] = $this->observation->latitude;
            $resource['longitude'] = $this->observation->longitude;
            $resource['accuracy'] = $this->observation->accuracy;
        }

        $resource['photos'] = PublicPhotoResource::collection($this->photos->filter->public_url);
        if ($isPrivileged) {
            $resource['observed_by'] = UserResource::make($this->observedBy);
            $resource['identified_by'] = UserResource::make($this->identifiedBy);
        } else {
            $resource['observed_by'] = ObscuredUserResource::make($this->observedBy);
            $resource['identified_by'] = ObscuredUserResource::make($this->identifiedBy);
        }
        $resource['activity'] = ActivityResource::collection($this->activity);

        return $resource;
    }
}
