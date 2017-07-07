<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taxon extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'taxa';

    public function observations()
    {
        return $this->hasMany(Observation::class);
    }

    public function approvedObservations()
    {
        return $this->observations()->approved();
    }

    public function unapprovedObservations()
    {
        return $this->observations()->unapproved();
    }

    public function mgrsFields()
    {
        return $this->approvedObservations
            ->pluck('mgrs_field')
            ->unique()
            ->values()
            ->all();
    }
}
