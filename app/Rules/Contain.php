<?php

namespace App\Rules;

use Illuminate\Validation\Rules\In;

class Contain extends In
{
    /**
     * The name of the rule.
     *
     * @var string
     */
    protected $rule = 'contain';
}
