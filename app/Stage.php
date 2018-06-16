<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    public function getNameTranslationAttribute()
    {
        return trans('stages.'.$this->name);
    }
}
