<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Facades\Purify;

class TaxonTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['native_name', 'description'];

    public function getDescriptionAttribute($value)
    {
        return Purify::clean($value);
    }
}
