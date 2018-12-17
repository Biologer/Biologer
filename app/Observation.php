<?php

namespace App;

class Observation extends Model
{
    const SEX_OPTIONS = [
        'male', 'female',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'accuracy' => 'integer',
        'elevation' => 'integer',
        'year' => 'integer',
        'month' => 'integer',
        'day' => 'integer',
        'number' => 'integer',
    ];

    /**
     * Get observations created by given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\User  $user  [description]
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedBy($query, User $user)
    {
        return $query->where('created_by_id', $user->id);
    }

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
        return $query->where(function ($query) {
            return $query->whereNotNull('year')
                ->whereNotNull('month')
                ->whereNotNull('day');
        });
    }

    /**
     * Get only observations whos taxon is curated by given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\User  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTaxonCuratedBy($query, User $user)
    {
        return $query->doesntHave('taxon')
            ->orWhereHas('taxon', function ($taxon) use ($user) {
                return $taxon->curatedBy($user);
            });
    }

    /**
     * Get only observations for taxon with given scientific or native name,
     * with ability to include children of matched taxa.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $name
     * @param  bool  $includeChildren
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForTaxonWithScientificOrNativeName($query, $name, $includeChildren = false)
    {
        return $query->whereHas('taxon', function ($query) use ($name, $includeChildren) {
            $query->withScientificOrNativeName($name);

            if ($includeChildren) {
                $query->orHasAncestorsWithScientificOrNativeName($name);
            }

            return $query;
        });
    }

    /**
     * Get only observations for taxon with given ID,
     * with ability to include children of matched taxon.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $taxonId
     * @param  bool  $includeChildren
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForTaxonWithId($query, $taxonId, $includeChildren = false)
    {
        return $query->whereHas('taxon', function ($query) use ($taxonId, $includeChildren) {
            $query->whereId($taxonId);

            if ($includeChildren) {
                $query->orHasAncestorWithId($taxonId);
            }

            return $query;
        });
    }

    /**
     * User that has submited this observation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Taxon that is observed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taxon()
    {
        return $this->belongsTo(Taxon::class);
    }

    /**
     * Stage of the specimen observed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stage()
    {
        return $this->belongsTo(Stage::class);
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
     * Observation types.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function types()
    {
        return $this->belongsToMany(
            ObservationType::class,
            'observation_observation_type',
            'observation_id',
            'type_id'
        );
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
     * Photos of the observation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class);
    }

    /**
     * Public photos of the observation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function publicPhotos()
    {
        return $this->photos()->public();
    }

    /**
     * Get sex translation.
     *
     * @return string
     */
    public function getSexTranslationAttribute()
    {
        return $this->sex ? trans('labels.sexes.'.$this->sex) : null;
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

    /**
     * Approve observation.
     *
     * @return void
     */
    public function approve()
    {
        if (is_null($this->approved_at)) {
            $this->forceFill(['approved_at' => $this->freshTimestamp()])->save();
        }
    }

    /**
     * Revert appproving observation.
     *
     * @return void
     */
    public function unapprove()
    {
        if (! is_null($this->approved_at)) {
            $this->forceFill(['approved_at' => null])->save();
        }
    }

    /**
     * Check if observation is approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return ! empty($this->approved_at);
    }

    /**
     * Check if observation is created by given user.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isCreatedBy(User $user)
    {
        return $this->creator->is($user);
    }

    /**
     * Check if given user shoud curate observation.
     *
     * @param  \App\User  $user
     * @param  bool  $evenWithoutTaxa
     * @return bool
     */
    public function shouldBeCuratedBy(User $user, $evenWithoutTaxa = true)
    {
        return $this->taxon ? $this->taxon->isCuratedBy($user) : $evenWithoutTaxa;
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            // Observer is the author of the photos, we need to update accordingly.
            if ($model->isDirty('observer')) {
                $model->photos()->update(['author' => $model->observer]);
            }
        });

        static::deleting(function ($model) {
            $model->photos->each->delete();
        });
    }
}
