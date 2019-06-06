<?php

namespace App\Filters\User;

class NameLike
{
    public function apply($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            $words = explode(' ', $value);

            foreach ($words as $word) {
                $query->orWhere('first_name', 'like', $word.'%')
                    ->orWhere('last_name', 'like', $word.'%');
            }
        });
    }
}
