<?php

namespace App;

class SpeciesGroupPaginator
{
    /**
     * @var \App\ViewGroup
     */
    protected $group;

    /**
    * @var \App\Taxon
    */
    protected $species;

    /**
     * Constructor.
     *
     * @param \App\ViewGroup  $group
     * @param \App\Taxon  $species
     */
    public function __construct(ViewGroup $group, Taxon $species)
    {
        $this->group = $group;
        $this->species = $species;
    }

    /**
     * Get the URL for a given species.
     *
     * @param  int|null  $speciesId
     * @return string
     */
    public function url($speciesId = null)
    {
        return route('groups.species.show', [
            'group' => $this->group->id,
            'species' => $speciesId ?? $this->species->id,
        ]);
    }

    /**
     * Get URL of species list for group.
     *
     * @return string
     */
    public function indexUrl()
    {
        return route('groups.species.index', [
            'group' => $this->group->id,
        ]);
    }

    /**
     * The URL for the next page, or null.
     *
     * @return string|null
     */
    public function nextUrl()
    {
        if ($this->isLast()) {
            return null;
        }

        return $this->url($this->nextId());
    }

    protected function nextId()
    {
        return $this->speciesIds()->slice($this->currentPosition() + 1, 1)->first();
    }

    /**
     * Get the URL for the previous page, or null.
     *
     * @return string|null
     */
    public function previousUrl()
    {
        if ($this->isFirst()) {
            return null;
        }

        return $this->url($this->previousId());
    }

    protected function previousId()
    {
        return $this->speciesIds()->slice($this->currentPosition() - 1, 1)->first();
    }

    /**
     * Get all of the items being paginated.
     *
     * @return array
     */
    public function currentPosition()
    {
        return $this->speciesIds()->search($this->species->id);
    }

    /**
     * Get the "id" of the first item being paginated.
     *
     * @return int
     */
    protected function firstId()
    {
        return $this->group->speciesIds()->first();
    }

    /**
     * Get the "id" of the last item being paginated.
     *
     * @return int
     */
    protected function lastId()
    {
        return $this->group->speciesIds()->last();
    }


    /**
     * Determine if there are enough items to split into multiple pages.
     *
     * @return bool
     */
    public function hasMultiple()
    {
        return $this->group->speciesIds()->count() > 1;
    }

    /**
     * Determine if there is more items in the data store.
     *
     * @return bool
     */
    public function isLast()
    {
        return $this->species->id === $this->lastId();
    }

    /**
     * Determine if this is the first item in the data store.
     *
     * @return bool
     */
    public function isFirst()
    {
        return $this->species->id === $this->firstId();
    }

    /**
     * Get current species.
     *
     * @return \App\Taxon
     */
    public function current()
    {
        return $this->species;
    }

    /**
     * Get group.
     *
     * @return \App\ViewGroup
     */
    public function group()
    {
        return $this->group;
    }

    /**
     * IDs of species in the group.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function speciesIds()
    {
        return $this->group->speciesIds();
    }

    public function __get($property)
    {
        return $this->species->{$property};
    }

    public function __call($method, $arguments)
    {
        return $this->species->{$method}(...$arguments);
    }
}
