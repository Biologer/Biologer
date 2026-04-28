<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Carbon;

class UpdatedAfter
{
    /**
     * Apply "updated after" filter in UTC.
     *
     * @param  Builder|Relation  $query
     * @param  mixed             $value  Epoch seconds (preferred) or ISO8601 string
     * @param  string            $column Column to filter (default: 'updated_at')
     * @return Builder|Relation
     */
    public function apply($query, $value, string $column = 'updated_at')
    {
        if ($value === null || $value === '') {
            return $query;
        }

        // Fallback if the filter system passed 'updated_after' instead of 'updated_at'
        if ($column === 'updated_after') {
            $column = 'updated_at';
        }

        // Normalize to a UTC Carbon instance
        $tsUtc = is_numeric($value)
            ? Carbon::createFromTimestampUTC((int) $value)
            : Carbon::parse($value)->utc();

        // MySQL runs in local time, so we convert UTC parameter to session time zone.
        $query->whereRaw(
            sprintf('`%s` >= CONVERT_TZ(?, "+00:00", @@session.time_zone)', $column),
            [$tsUtc->format('Y-m-d H:i:s')]
        );

        return $query;
    }
}
