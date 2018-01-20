<?php

namespace App\Support;

class NullMgrs
{
    public function to10k()
    {
        return $this->convert();
    }

    public function convert()
    {
        return;
    }
}
