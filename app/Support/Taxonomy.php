<?php

namespace App\Support;

class Taxonomy
{
    public static function checkOrFailUsingTaxonomy()
    {
        if (
            ! config('biologer.taxonomy_status', false)
            or config('biologer.taxonomy_link', '') == ''
            or config('biologer.taxonomy_api_key', '') == ''
        ) {
            return false;
        }

        return config('biologer.taxonomy_link', '');
    }
}
