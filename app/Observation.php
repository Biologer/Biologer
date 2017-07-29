<?php

namespace App;

class Observation extends Model
{
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

    public function details()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function addComment(Comment $comment)
    {
        return $this->comments()->save($comment);
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
