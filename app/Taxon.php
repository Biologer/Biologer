<?php

namespace App;

class Taxon extends Model
{
    use Concerns\HasAncestry;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'taxa';

    protected $appends = ['category'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    // protected $with = ['parent'];

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

    public function getCategoryAttribute()
    {
        $categories =static::getCategories();

        return $categories[$this->category_level];
    }

    /**
     * Get list of MGRS fields the taxon was observed at.
     *
     * @return array
     */
    public function mgrs()
    {
        return $this->approvedObservations()
            ->pluck('mgrs10k')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Taxon categories as options for frontend.
     * @return array
     */
    public static function getCategoryOptions()
    {
        return array_map(function ($category, $index) {
            return [
                'value' => $index,
                'name' => $category,
            ];
        }, static::getCategories(), array_keys(static::getCategories()));
    }
}
