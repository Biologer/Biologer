<?php

namespace App\Contracts;

interface FlatArrayable
{
    /**
     * Serialize to "flat" array.
     *
     * @return array
     */
    public function toFlatArray();
}
