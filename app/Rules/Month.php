<?php

namespace App\Rules;

use Illuminate\Support\Carbon;
use Illuminate\Contracts\Validation\Rule;

class Month implements Rule
{
    /**
     * @var string|int
     */
    protected $year;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($year)
    {
        $this->year = $year;
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
        if (! $this->yearIsValid() || $value <= 0) {
            return false;
        }

        $now = Carbon::now();

        if ($now->year === (int) $this->year) {
            return $value <= $now->month;
        }

        return $value <= 12;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid month.';
    }

    protected function yearIsValid()
    {
        return $this->year && is_numeric($this->year) && $this->year > 0;
    }
}
