<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Activity;

class TransectVisit extends Model
{
    /**
     * Transect section this observation belongs to (if any).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transectSection()
    {
        return $this->belongsTo(TransectSection::class);
    }

    /**
     * Field observation that belong to the transect visit.
     *
     * @return HasMany
     */
    public function fieldObsevations()
    {
        return $this->hasMany(FieldObservation::class, 'transect_visit_id');
    }

    /**
     * View group that has access to the timed count observation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function viewGroup()
    {
        return $this->belongsTo(ViewGroup::class, 'view_groups_id');
    }

    /**
     * Activity recorded on the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest()->latest('id');
    }

    /**
     * Get transect visits created by given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\User  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedBy($query, User $user)
    {
        return $query->where('created_by_id', $user->id);
    }

    /**
     * Check if transect visit is created by given user.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isCreatedBy(User $user)
    {
        return $this->creator->is($user);
    }

    /**
     * User that has submitted this transect visit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->fieldObservations()->delete();
            $model->activity()->delete();
        });
    }
}
