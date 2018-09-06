<?php

namespace App\Filters\User;

class Search
{
    public function apply($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            return $query->orWhere('first_name', 'like', '%'.$value.'%')
                ->orWhere('last_name', 'like', '%'.$value.'%')
                ->orWhere('email', 'like', '%'.$value.'%')
                ->orWhere('institution', 'like', '%'.$value.'%');
        });
    }
}
