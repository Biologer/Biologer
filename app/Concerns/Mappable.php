<?php

namespace App\Concerns;

use LogicException;
use Sofa\Eloquence\Mappable as BaseMappable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Mappable
{
    use BaseMappable;

    /**
     * Get the keys from relation in order to join the table.
     *
     * NOTE: This is override of method on Mappable trait to fix compatibility
     * with Laravel 5.8 where the  name of `getQualifiedForeignKey` method on
     * BelongsTo relation has been changed to `getQualifiedForeignKeyName`.
     *
     * @param  \Illuminate\Database\Eloquent\Relations\Relation  $relation
     * @return array
     *
     * @throws \LogicException
     */
    protected function getJoinKeys(Relation $relation)
    {
        if ($relation instanceof HasOne || $relation instanceof MorphOne) {
            return [$relation->getQualifiedForeignKeyName(), $relation->getQualifiedParentKeyName()];
        }

        if ($relation instanceof BelongsTo && ! $relation instanceof MorphTo) {
            return [$relation->getQualifiedForeignKeyName(), $relation->getQualifiedOwnerKeyName()];
        }

        $class = get_class($relation);

        throw new LogicException(
            "Only HasOne, MorphOne and BelongsTo mappings can be queried. {$class} given."
        );
    }
}
