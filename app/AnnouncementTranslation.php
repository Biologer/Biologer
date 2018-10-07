<?php

namespace App;

use Stevebauman\Purify\Facades\Purify;
use Illuminate\Database\Eloquent\Model;

class AnnouncementTranslation extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'message'];

    public function getMessageAttribute($value)
    {
        return Purify::clean($value);
    }
}
