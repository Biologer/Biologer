<?php

namespace App;

use App\Contracts\FlatArrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Activity;

class TransectCountObservation extends Model implements FlatArrayable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'primary_habitat',
        'location',
        'length',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'transect_count_observation_id' => 'integer',
    ];

    /**
     * Transect sections that belong to the transect count.
     *
     * @return HasMany
     */
    public function transectSections()
    {
        return $this->hasMany(TransectSection::class, 'transect_count_observation_id');
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
     * Get transect count observations created by given user.
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
     * Check if transect count observation is created by given user.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isCreatedBy(User $user)
    {
        return $this->creator->is($user);
    }

    /**
     * User that has submitted this transect count observation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->section_name,
            'description' => $this->section_description,
            'location' => $this->location,
            'length' => $this->length,
            'primary_habitat' => $this->primary_habitat,
        ];
    }

    /**
     * Convert the model instance to a flat array.
     *
     * @return array
     */
    public function toFlatArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->section_name,
            'description' => $this->section_description,
            'location' => $this->location,
            'length' => $this->length,
            'primary_habitat' => $this->primary_habitat,
        ];
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
            $model->transectSections()->delete();
            $model->activity()->delete();
        });
    }
}
