<?php

namespace App;

class Observation extends Model
{
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['taxon'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'accuracy' => 'integer',
        'altitude' => 'integer',
        'year' => 'integer',
        'month' => 'integer',
        'day' => 'integer',
    ];

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

    public function taxon()
    {
        return $this->belongsTo(Taxon::class);
    }

    /**
     * Details of different observation types.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function details()
    {
        return $this->morphTo();
    }

    /**
     * Comments about the observation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Add a comment to the observation.
     *
     * @param  string  $comment
     * @return Comment
     */
    public function addNewComment($comment)
    {
        return $this->comments()->save(new Comment([
            'body' => $comment,
            'user_id' => auth()->user()->id,
        ]));
    }

    /**
     * Check if date is complete.
     *
     * @return bool
     */
    public function isDateComplete()
    {
        return ! (is_null($this->year)
            || is_null($this->month)
            || is_null($this->day));
    }
}
