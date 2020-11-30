<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'url' => url("{$this->public_url}?v={$this->updated_at->timestamp}"),
        ];
    }
}
