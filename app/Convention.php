<?php

namespace App;

class Convention extends Model
{
    /**
     * Taxa the convention applies to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taxa()
    {
        return $this->belongsToMany(Taxon::class);
    }
}
