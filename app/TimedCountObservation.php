<?php

namespace App;

use App\Contracts\FlatArrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimedCountObservation extends Model implements FlatArrayable
{
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
     * User that made the observation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function observedBy()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * User that has identified field observation taxon.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function identifiedBy()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get observations created by given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\User  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedBy($query, User $user)
    {
        return $query->whereHas('observation', function ($q) use ($user) {
            return $q->createdBy($user);
        });
    }

    /**
     * Get only observations of taxa curated by given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\User  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCuratedBy($query, User $user)
    {
        return $query->whereHas('observation', function ($observation) use ($user) {
            return $observation->taxonCuratedBy($user);
        });
    }

    /**
     * Check if observation is created by given user.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isCreatedBy(User $user)
    {
        return $this->observation->isCreatedBy($user);
    }

    /**
     * Check if given user should curate this observation.
     *
     * @param  \App\User  $user
     * @param  bool  $evenWithoutTaxa
     * @return bool
     */
    public function shouldBeCuratedBy(User $user, $evenWithoutTaxa = true)
    {
        return $this->observation->shouldBeCuratedBy($user, $evenWithoutTaxa);
    }

    /**
     * Get curators for the observation.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function curators()
    {
        $taxon = $this->observation->taxon;

        if (empty($taxon)) {
            return User::whereHas('roles', function ($query) {
                $query->whereName('curator');
            });
        }

        $curators = $taxon->load('ancestors.curators')
            ->ancestors
            ->pluck('curators')
            ->push($taxon->curators)
            ->flatten();

        return \Illuminate\Database\Eloquent\Collection::make($curators)->unique();
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
     * Convert the model instance to a flat array.
     *
     * @return array
     */
    public function toFlatArray()
    {
        return [
            'id' => $this->id,
            'start_time' => $this->start_time ? $this->start_time->toIso8601String() : null,
            'end_time' => $this->end_time ? $this->end_time->toIso8601String() : null,
            'count_duration' => $this->count_duration,
            'cloud_cover' => $this->cloud_cover,
            'atmospheric_pressure' => $this->atmospheric_pressure,
            'humidity' => $this->humidity,
            'temperature' => $this->temperature,
            'wind_direction' => $this->wind_direction,
            'wind_speed' => $this->wind_speed,
            'habitat' => $this->habitat,
            'area' => $this->area,
            'route_length' => $this->route_length,
            'view_groups_id' => $this->view_groups_id,
        ];
    }
}
