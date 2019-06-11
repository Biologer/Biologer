<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhotoResource extends JsonResource
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
            'metadata' => $this->metadata,
            'path' => $this->path,
            'url' => "{$this->url}?v={$this->updated_at->timestamp}",
        ];
    }
}
