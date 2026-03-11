<?php

namespace App;

use App\Contracts\FlatArrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Activity;

class TransectVisit extends Model implements FlatArrayable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_time',
        'end_time',
        'cloud_cover',
        'atmospheric_pressure',
        'humidity',
        'temperature',
        'wind_direction',
        'wind_speed',
        'comments',
        'transect_section_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'cloud_cover' => 'integer',
        'atmospheric_pressure' => 'float',
        'humidity' => 'integer',
        'temperature' => 'float',
        'wind_direction' => 'string',
        'comments' => 'string',
        'transect_section_id' => 'integer',
    ];

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
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'cloud_cover' => $this->cloud_cover,
            'atmospheric_pressure' => $this->atmospheric_pressure,
            'humidity' => $this->humidity,
            'temperature' => $this->temperature,
            'wind_direction' => $this->wind_direction,
            'wind_speed' => $this->wind_speed,
            'comments' => $this->comments,
            'activity' => $this->activity,
            'transect_section_id' => $this->transect_section_id,
            'transect_count_observation_id' => $this->transectSection->transect_count_observation_id,
        ];
    }

    /**
     * Convert the model instance to a flat array.
     *
     * @return array
     */
    public function toFlatArray(): array
    {
        return [
            'id' => $this->id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'cloud_cover' => $this->cloud_cover,
            'atmospheric_pressure' => $this->atmospheric_pressure,
            'humidity' => $this->humidity,
            'temperature' => $this->temperature,
            'wind_direction' => $this->wind_direction,
            'wind_speed' => $this->wind_speed,
            'comments' => $this->comments,
            'activity' => $this->activity,
            'transect_section_id' => $this->transect_section_id,
            'transect_count_observation_id' => $this->transectSection->transect_count_observation_id,
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
            $model->fieldObservations()->delete();
            $model->activity()->delete();
        });
    }
}
