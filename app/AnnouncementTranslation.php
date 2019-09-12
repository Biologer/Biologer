<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Facades\Purify;

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

    /**
     * Strip forbidden tags from announcement message.
     *
     * @param  string  $value
     * @return string
     */
    public function getMessageAttribute($value)
    {
        return Purify::clean($value);
    }
}
