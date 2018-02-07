<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxonTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['native_name', 'description'];
}
