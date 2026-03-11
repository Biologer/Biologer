<?php

namespace App;

use App\Contracts\FlatArrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Activity;

class TransectSection extends Model implements FlatArrayable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'length',
        'primary_habitat',
        'secondary_habitat',
        'land_tenure',
        'land_management',
        'transect_count_observation_id',
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
     * Transect count this observation belongs to (if any).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transectCountObservation()
    {
        return $this->belongsTo(TransectCountObservation::class);
    }

    /**
     * Transect visits that belong to the transect section.
     *
     * @return HasMany
     */
    public function transectVisits()
    {
        return $this->hasMany(TransectVisit::class, 'transect_section_id');
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
     * Get transect sections created by given user.
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
     * Check if transect section is created by given user.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isCreatedBy(User $user)
    {
        return $this->creator->is($user);
    }

    /**
     * User that has submitted this transect section.
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
            'name' => $this->name,
            'description' => $this->description,
            'length' => $this->length,
            'primary_habitat' => $this->primary_habitat,
            'secondary_habitat' => $this->secondary_habitat,
            'land_tenure' => $this->land_tenure,
            'land_management' => $this->land_management,
            'transect_count_observation_id' => $this->transect_count_observation_id,
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
            'name' => $this->name,
            'description' => $this->description,
            'length' => $this->length,
            'primary_habitat' => $this->primary_habitat,
            'secondary_habitat' => $this->secondary_habitat,
            'land_tenure' => $this->land_tenure,
            'land_management' => $this->land_management,
            'transect_count_observation_id' => $this->transect_count_observation_id,
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
            $model->transectVisits()->delete();
            $model->activity()->delete();
        });
    }
}
