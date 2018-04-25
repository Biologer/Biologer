<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewGroupTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'description'];
}
