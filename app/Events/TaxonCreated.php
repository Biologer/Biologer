<?php

namespace App\Events;

use App\Models\Taxon;
use Illuminate\Queue\SerializesModels;

class TaxonCreated
{
    use SerializesModels;

    /**
     * @var \App\Models\Taxon
     */
    public $taxon;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Taxon $taxon)
    {
        $this->taxon = $taxon;
    }
}
