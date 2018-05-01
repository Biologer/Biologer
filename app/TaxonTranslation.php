<?php

namespace App;

use Stevebauman\Purify\Facades\Purify;
use Illuminate\Database\Eloquent\Model;

class TaxonTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['native_name', 'description'];

    public function getDescriptionAttribute($value)
    {
        return Purify::clean($value);
    }
}
