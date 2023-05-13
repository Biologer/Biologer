<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Synonym extends Model
{
    use Filterable;

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $fillable = ['name', 'author', 'taxon_id'];

    /**
     * One synonym for one taxon.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taxon()
    {
        return $this->belongsTo(Taxon::class);
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'taxon_id' => $this->taxon_id,
            'name' => $this->name,
            'author' => $this->author,
        ];
    }

    /**
     * Serialize field observation to a flat array.
     * Mostly used for the frontend and diffing.
     *
     * @return array
     */
    public function toFlatArray()
    {
        return [
            'id' => $this->id,
            'taxon_id' => $this->taxon_id,
            'name' => $this->name,
            'author' => $this->author,
        ];
    }
}
