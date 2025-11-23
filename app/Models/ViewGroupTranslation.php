<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewGroupTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'description'];

    protected $casts = [
        'view_group_id' => 'integer',
    ];
}
