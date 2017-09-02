<?php

namespace App;

class Comment extends Model
{
    /**
     * Relation to Model that can be commented.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }
}
