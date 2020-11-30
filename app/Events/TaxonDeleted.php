<?php

namespace App\Events;

use App\Taxon;
use Illuminate\Queue\SerializesModels;

class TaxonDeleted
{
    use SerializesModels;

    /**
     * @var \App\Taxon
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
