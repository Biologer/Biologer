<?php

namespace App\Exports\Taxa;

use App\Taxon;
use App\Export;
use App\Exports\BaseExport;

class CustomTaxaExport extends BaseExport
{
    /**
     * Column labels and names.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function columnData()
    {
        return collect(array_keys(Taxon::RANKS))->map(function ($rank) {
            return [
                'label' => trans("taxonomy.{$rank}"),
                'value' => $rank,
            ];
        })->concat([
            [
                'label' => trans('labels.id'),
                'value' => 'id',
            ],
            [
                'label' => trans('labels.taxa.author'),
                'value' => 'author',
            ],
            [
                'label' => trans('labels.taxa.restricted'),
                'value' => 'restricted',
            ],
            [
                'label' => trans('labels.taxa.allochthonous'),
                'value' => 'allochthonous',
            ],
            [
                'label' => trans('labels.taxa.invasive'),
                'value' => 'invasive',
            ],
            [
                'label' => trans('labels.taxa.fe_old_id'),
                'value' => 'fe_old_id',
            ],
            [
                'label' => trans('labels.taxa.fe_id'),
                'value' => 'fe_id',
            ],
            [
                'label' => trans('labels.taxa.stages'),
                'value' => 'stages',
            ],
            [
                'label' => trans('labels.taxa.conservation_legislations'),
                'value' => 'conservation_legislations',
            ],
            [
                'label' => trans('labels.taxa.red_lists'),
                'value' => 'red_lists',
            ],
        ]);
    }

    /**
     * Database query to get the data for export.
     *
     * @param  \App\Export  $export
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query(Export $export)
    {
        return Taxon::with(['parent', 'ancestors'])->filter($export->filter)->orderBy('id');
    }

    /**
     * Extract needed data from item.
     *
     * @param  \App\Taxon  $item
     * @return array
     */
    protected function transformItem($item)
    {
        $transformed = [
            'id' => $item->id,
            $item->rank => $item->name,
            'author' => $item->author,
            'restricted' => $item->restricted ? __('Yes') : __('No'),
            'allochthonous' => $item->allochthonous ? __('Yes') : __('No'),
            'invasive' => $item->invasive ? __('Yes') : __('No'),
            'fe_old_id' => $item->fe_old_id,
            'fe_id' => $item->fe_id,
            'stages' => $item->stages->map->name_translation->implode(', '),
            'conservation_legislations' => $item->conservationLegislations->map->name->implode(', '),
            'conservation_documents' => $item->conservationDocuments->map->name->implode(', '),
            'red_lists' => $item->redLists->map(function ($redList) {
                return "{$redList->name} ({$redList->pivot->category})";
            })->implode(', '),
        ];

        foreach ($item->ancestors as $ancestor) {
            $transformed[$ancestor->rank] = $ancestor->name;
        }

        return $transformed;
    }
}
