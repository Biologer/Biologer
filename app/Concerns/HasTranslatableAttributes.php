<?php

namespace App\Concerns;

trait HasTranslatableAttributes
{
    /**
     * Get attribute translations forthe forms.
     *
     * @param  string  $attribute
     * @return \Illuminate\Support\Collection
     */
    public function getAttributeTranslations($attribute)
    {
        return collect(\LaravelLocalization::getSupportedLanguagesKeys())
            ->mapWithKeys(function ($locale) use ($attribute) {
                return [$locale => $this->translateOrNew($locale)->{$attribute}];
            });
    }
}
