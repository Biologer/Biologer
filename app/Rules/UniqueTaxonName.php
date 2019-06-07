<?php

namespace App\Rules;

use App\Taxon;

use Illuminate\Contracts\Validation\Rule;

class UniqueTaxonName implements Rule
{
    /**
     * @var int|null
     */
    private $parentId;

    /**
     * @var int|null
     */
    private $ignoreId;

    /**
     * Create a new rule instance.
     *
     * @param  int|null  $parentId
     * @param  int|null  $ignoreId
     * @return void
     */
    public function __construct($parentId = null, $ignoreId = null)
    {
        $this->parentId = $parentId;
        $this->ignoreId = $ignoreId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_null($this->parentId)) {
            return $this->validateRoot($value);
        }

        if ($parent = Taxon::find($this->parentId)) {
            return $this->validateGroupDescendants($parent, $value);
        }

        return true;
    }

    /**
     * In case the validated taxon is supposed to be root, we check other roots.
     *
     * @param  string  $value
     * @return bool
     */
    private function validateRoot($value)
    {
        return $this->checkQuery(Taxon::roots(), $value);
    }

    /**
     * In case it has parent, we check get the root and check uniqueness in that tree.
     *
     * @param  \App\Taxon  $parent
     * @param  string  $value
     * @return bool
     */
    private function validateGroupDescendants($parent, $value)
    {
        $root = $parent->isRoot() ? $parent : $parent->ancestors()->roots()->first();

        return $this->checkQuery($root->descendants(), $value);
    }

    /**
     * Construct the checking part of the query and execute it.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $value
     * @return bool
     */
    private function checkQuery($query, $value)
    {
        return $query->where('name', 'like', trim($value))->when($this->ignoreId, function ($query) {
            $query->where('id', '<>', $this->ignoreId);
        })->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.unique_taxon_name');
    }
}
