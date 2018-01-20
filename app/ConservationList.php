<?php

namespace App;

class ConservationList extends Model
{
    /**
     * Taxa that is listed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taxa()
    {
        return $this->belongsToMany(Taxon::class);
    }
}
