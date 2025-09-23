<?php

namespace App;

use App\Concerns\CanMemoize;
use App\Concerns\MappedSorting;
use App\Contracts\FlatArrayable;
use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Activity;

class TimedCountObservation extends Model implements FlatArrayable
{
    use HasFactory, CanMemoize, Filterable, MappedSorting;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'year',
        'month',
        'day',
        'start_time',
        'end_time',
        'count_duration',
        'cloud_cover',
        'atmospheric_pressure',
        'humidity',
        'temperature',
        'wind_direction',
        'wind_speed',
        'habitat',
        'comments',
        'area',
        'route_length',
        'observer',
        'observed_by_id',
        'created_by_id',
        'view_groups_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'day' => 'integer',
        'start_time' => 'time',
        'end_time' => 'time',
        'count_duration' => 'integer',
        'cloud_cover' => 'integer',
        'atmospheric_pressure' => 'float',
        'humidity' => 'integer',
        'temperature' => 'float',
        'wind_direction' => 'string',
        'wind_speed' => 'float',
        'habitat' => 'string',
        'comments' => 'string',
        'area' => 'float',
        'route_length' => 'float',
    ];

    /**
     * Field observations that belong to the timed count.
     *
     * @return HasMany
     */
    public function fieldObservations()
    {
        return $this->hasMany(FieldObservation::class, 'timed_count_id');
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
     * User that made the observation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function observedBy()
    {
        return $this->belongsTo(User::class);
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
     * Get timed count observations created by given user.
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
     * Check if timed count observation is created by given user.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isCreatedBy(User $user)
    {
        return $this->creator->is($user);
    }

    /**
     * User that has submitted this timed count observation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * List of fields that field observations can be sorted by.
     *
     * @return array
     */
    public static function sortableFields()
    {
        return [
            'id', 'year', 'month', 'day',
        ];
    }

    /**
     * Filter definitions.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'sort_by' => \App\Filters\SortBy::class,
            'year' => \App\Filters\FieldObservation\ObservationAttribute::class,
            'month' => \App\Filters\FieldObservation\ObservationAttribute::class,
            'day' => \App\Filters\FieldObservation\ObservationAttribute::class,
        ];
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
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'count_duration' => $this->count_duration,
            'cloud_cover' => $this->cloud_cover,
            'atmospheric_pressure' => $this->atmospheric_pressure,
            'humidity' => $this->humidity,
            'temperature' => $this->temperature,
            'wind_direction' => $this->wind_direction,
            'wind_speed' => $this->wind_speed,
            'habitat' => $this->habitat,
            'comments' => $this->comments,
            'area' => $this->area,
            'route_length' => $this->route_length,
            'view_groups_id' => $this->view_groups_id,
            'activity' => $this->activity,
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
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'count_duration' => $this->count_duration,
            'cloud_cover' => $this->cloud_cover,
            'atmospheric_pressure' => $this->atmospheric_pressure,
            'humidity' => $this->humidity,
            'temperature' => $this->temperature,
            'wind_direction' => $this->wind_direction,
            'wind_speed' => $this->wind_speed,
            'habitat' => $this->habitat,
            'comments' => $this->comments,
            'area' => $this->area,
            'route_length' => $this->route_length,
            'view_groups_id' => $this->view_groups_id,
            'activity' => $this->activity,
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
