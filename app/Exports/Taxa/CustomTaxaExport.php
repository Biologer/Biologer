<?php

namespace App\Exports\Taxa;

use App\Export;
use App\Exports\BaseExport;
use App\Taxon;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CustomTaxaExport extends BaseExport
{
    /**
     * Column labels and names.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function columnData()
    {
        $locales = collect(LaravelLocalization::getSupportedLocales());

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
            [
                'label' => trans('labels.taxa.uses_atlas_codes'),
                'value' => 'uses_atlas_codes',
            ],
        ])->concat($locales->map(function ($locale, $localeCode) {
            $nativeName = trans('labels.taxa.native_name');
            $localeTranslation = trans('languages.'.$locale['name']);

            return [
                'label' => "{$nativeName} - {$localeTranslation}",
                'value' => 'native_name_'.Str::snake($localeCode),
            ];
        }))->concat($locales->map(function ($locale, $localeCode) {
            $description = trans('labels.taxa.description');
            $localeTranslation = trans('languages.'.$locale['name']);

            return [
                'label' => "{$description} - {$localeTranslation}",
                'value' => 'description_'.Str::snake($localeCode),
            ];
        }));
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
            'uses_atlas_codes' => $item->uses_atlas_codes ? __('Yes') : __('No'),
        ];

        foreach ($item->ancestors as $ancestor) {
            $transformed[$ancestor->rank] = $ancestor->name;
        }

        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $locale) {
            $translation = $item->translateOrNew($localeCode);

            $transformed['native_name_'.Str::snake($localeCode)] = $translation->native_name;
            $transformed['description_'.Str::snake($localeCode)] = $translation->description;
        }

        return $transformed;
    }
}
