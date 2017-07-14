<?php

namespace App;

use Hamcrest\SelfDescribing;
use Illuminate\Database\Eloquent\Model;

class Taxon extends Model
{
    use Concerns\HasAncestry;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'taxa';

    /**
     * Observations relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function observations()
    {
        return $this->hasMany(Observation::class);
    }

    /**
     * Approved observations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function approvedObservations()
    {
        return $this->observations()->approved();
    }

    /**
     * Unapproved observations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unapprovedObservations()
    {
        return $this->observations()->unapproved();
    }

    /**
     * Get list of MGRS fields the taxon was observed at.
     *
     * @return array
     */
    public function mgrs()
    {
        return $this->approvedObservations()
            ->pluck('mgrs_field')
            ->unique()
            ->values()
            ->all();
    }
}
