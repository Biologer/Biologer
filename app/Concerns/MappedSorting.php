<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;

trait MappedSorting
{
    /**
     * Here we keep track which relations have been joined.
     *
     * @var array
     */
    protected $joinedRelationsForSorting = [];

    /**
     * Query ordering with or without using column on related tables.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $orderBy
     * @param  string  $sortOrder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByMapped($query, $orderBy, $sortOrder = 'asc')
    {
        if (! $this->shouldOrderByRelationColumn($orderBy)) {
            $mappedOrderBy = $this->mappedOrderBy($orderBy);

            if ($mappedOrderBy !== $orderBy) {
                $query->addSelect("{$this->getTable()}.*", "{$orderBy} as {$mappedOrderBy}");
            }

            return $query->orderBy($mappedOrderBy, $sortOrder);
        }

        return $this->orderByRelated($query, $orderBy, $sortOrder);
    }

    /**
     * Get column mapped for sorting.
     *
     * @param  string  $orderBy
     * @return string
     */
    protected function mappedOrderBy($orderBy)
    {
        $sortMap = $this->sortMap();

        if (empty($sortMap[$orderBy])) {
            return $orderBy;
        }

        return $sortMap[$orderBy];
    }

    /**
     * Check if we should sort using column from relation.
     *
     * @param  string  $orderBy
     * @return bool
     */
    protected function shouldOrderByRelationColumn($orderBy)
    {
        return mb_strpos($this->mappedOrderBy($orderBy), '.') !== false;
    }

    /**
     * Order by using column from related model. Supports nested relations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $orderBy
     * @param  string  $sortOrder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function orderByRelated($query, $orderBy, $sortOrder)
    {
        $relations = explode('.', $this->mappedOrderBy($orderBy));
        $mappedColumn = array_pop($relations);
        $relationsNesting = [];

        $table = $this->getTable();

        foreach ($relations as $relation) {
            $relationsNesting[] = $relation;
            $relationKey = implode('.', $relationsNesting);

            if ($this->isJoinedForSorting($relationKey)) {
                continue;
            }

            $table = $this->joinWithTableForRelationForSorting($query, $relationsNesting);
        }

        $this->joinedRelationsForSorting = array_unique(
            array_merge($this->joinedRelationsForSorting, $relationsNesting)
        );

        return $query->addSelect([
            "{$this->getTable()}.*",
            "{$table}.{$mappedColumn} as {$orderBy}",
        ])->orderBy($orderBy, $sortOrder);
    }

    /**
     * Check if we have already joined table for needed relation.
     *
     * @param  string  $relation
     * @return bool
     */
    protected function isJoinedForSorting($relation)
    {
        return in_array($relation, $this->joinedRelationsForSorting);
    }

    /**
     * Join table based on relation.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $relationsNesting
     * @return string
     */
    protected function joinWithTableForRelationForSorting($query, $relationsNesting)
    {
        [$parent, $relationName] = $this->segmentForSortJoining($relationsNesting);

        $relation = $parent->{$relationName}();
        $related = $relation->getRelated();
        $table = $related->getTable();


        [$fk, $pk] = $this->getKeysForSortingJoin($relation);

        $query->leftJoin("{$table}", function ($join) use ($fk, $pk, $relation, $parent, $related) {
            $join->on($fk, '=', $pk);

            if ($relation instanceof MorphOne || $relation instanceof MorphTo) {
                $morphClass = ($relation instanceof MorphOne)
                    ? $parent->getMorphClass()
                    : $related->getMorphClass();

                $join->where($relation->getQualifiedMorphType(), '=', $morphClass);
            }
        });

        return $table;
    }

    /**
     * Get the keys from relation in order to join the table.
     *
     * @param  \Illuminate\Database\Eloquent\Relations\Relation  $relation
     * @return array
     *
     * @throws \LogicException
     */
    protected function getKeysForSortingJoin(Relation $relation)
    {
        if ($relation instanceof HasOne || $relation instanceof MorphOne) {
            return [$relation->getQualifiedForeignKeyName(), $relation->getQualifiedParentKeyName()];
        }

        if ($relation instanceof BelongsTo && ! $relation instanceof MorphTo) {
            return [$relation->getQualifiedForeignKeyName(), $relation->getQualifiedOwnerKeyName()];
        }

        $class = get_class($relation);

        throw new \LogicException(
            "Only HasOne, MorphOne and BelongsTo mappings can be queried. {$class} given."
        );
    }

    /**
     * Get last segment that should be joined.
     *
     * @param  array  $relationsNesting
     * @return array
     */
    protected function segmentForSortJoining(array $relationsNesting)
    {
        $model = new static;

        while (count($relationsNesting) > 1) {
            $relationName = array_shift($relationsNesting);

            $relation = $model->{$relationName}();

            $model = $relation->getRelated();
        }

        return [$model, $relationsNesting[0]];
    }

    /**
     * @return array
     */
    protected function sortMap()
    {
        return [];
    }
}
