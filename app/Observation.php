<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get only approved observations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    /**
     * Get only unapproved observations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnapproved($query)
    {
        return $query->whereNull('approved_at');
    }

    /**
     * Get only observations with full date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCompleteDate($query)
    {
        return $query->whereNotNull('year')
            ->whereNotNull('month')
            ->whereNotNull('day');
    }

    /**
     * Check if date is complete.
     *
     * @return bool
     */
    public function isDateComplete()
    {
        return ! (is_null($this->year) || is_null($this->month) || is_null($this->day));
    }
}
