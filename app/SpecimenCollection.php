<?php

namespace App;

use App\Filters\Filterable;

class SpecimenCollection extends Model
{
    use Filterable;

    /**
     * Filters that can be used on queries.
     *
     * @var array
     */
    protected function filters()
    {
        return [
            'sort_by' => \App\Filters\SortBy::class,
        ];
    }

    /**
     * List of fields that taxa can be sorted by.
     *
     * @return array
     */
    public static function sortableFields()
    {
        return [
            'id', 'name', 'code', 'institution_name', 'institution_code',
        ];
    }
}
