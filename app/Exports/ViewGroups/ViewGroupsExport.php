<?php

namespace App\Exports\ViewGroups;

use App\Models\Export;
use App\Exports\BaseExport;
use App\Models\ViewGroup;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ViewGroupsExport extends BaseExport
{
    /**
     * Column labels and names.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function columnData()
    {
        $locales = collect(LaravelLocalization::getSupportedLocales());

        return collect([
            [
                'label' => trans('labels.id'),
                'value' => 'id',
            ],
            [
                'label' => trans('labels.view_groups.parent'),
                'value' => 'parent_id',
            ],
            [
                'label' => trans('labels.view_groups.name'),
                'value' => 'name',
            ],
        ])->concat($locales->map(function ($locale, $localeCode) {
            $nativeName = trans('labels.view_groups.name');
            $localeTranslation = trans('languages.'.$locale['name']);

            return [
                'label' => "{$nativeName} - {$localeTranslation}",
                'value' => 'name_'.Str::snake($localeCode),
            ];
        }));
    }

    /**
     * Database query to get the data for export.
     *
     * @param  \App\Models\Export  $export
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query(Export $export)
    {
        return ViewGroup::with(['groups'])->filter($export->filter)->orderBy('id');
    }

    /**
     * Extract needed data from item.
     * All separators must be semicolon with space afterward ('; ').
     *
     * @param  \App\Taxon  $item
     * @return array
     */
    protected function transformItem($item)
    {
        $transformed = [
            'id' => $item->id,
            'parent_id' => $item->parent_id,
            'name' => $item->name,
        ];

        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $locale) {
            $translation = $item->translateOrNew($localeCode);

            $transformed['name_'.Str::snake($localeCode)] = $translation->name;
        }

        return $transformed;
    }
}
