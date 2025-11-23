<?php

namespace App\Http\Resources;

use App\Models\LiteratureObservation;
use Illuminate\Http\Resources\Json\JsonResource;

class LiteratureObservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge($this->resource->toFlatArray(), [
            'activity' => $this->when(
                $request->user()->can('viewActivityLog', LiteratureObservation::class),
                $this->activity
            ),
        ]);
    }
}
