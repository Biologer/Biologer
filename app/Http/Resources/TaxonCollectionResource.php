<?php

namespace App\Http\Resources;

use App\Taxon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaxonCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $request->user();

        $data = $this->collection->map(function (Taxon $taxon) use ($user, $request) {
            $taxon->can_edit = $user->can('update', $taxon);
            $taxon->can_delete = $user->can('delete', $taxon);

            $resource = $taxon->makeVisible(['can_edit', 'can_delete'])
                ->makeHidden(['curators', 'ancestors'])
                ->toArray();

            // Non-existing translations only cause confusion and trouble
            $resource['translations'] = $taxon->translations->filter->exists->toArray();

            if ($request->boolean('withGroupsIds')) {
                $resource['groups'] = $this->groups($taxon);
            }

            return $resource;
        });

        return [
            'data' => $data,
        ];
    }

    protected function groups($taxon)
    {
        return $taxon->groups->pluck('id')->concat(
            $taxon->ancestors->pluck('groups')->flatten()->pluck('id')
        )->unique();
    }
}
