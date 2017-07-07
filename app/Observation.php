<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    public function scopeUnapproved($query)
    {
        return $query->whereNull('approved_at');
    }
}
