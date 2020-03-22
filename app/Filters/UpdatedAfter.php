<?php

namespace App\Filters;

use Illuminate\Support\Carbon;

class UpdatedAfter
{
    public function apply($query, $value)
    {
        if (is_numeric($value)) {
            return $query->where('updated_at', '>=', Carbon::createFromTimestamp($value));
        }
    }
}
