<?php

namespace App\Rules;

use Illuminate\Support\Carbon;
use Illuminate\Contracts\Validation\Rule;

class Day implements Rule
{
    protected $year;

    protected $month;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
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
        if (!$this->yearIsValid() || !$this->monthIsValid() || $value <= 0) {
            return false;
        }

        $now = Carbon::now();
        $date = Carbon::create($this->year, $this->month);

        if ($now->year == $this->year && $now->month == $this->month) {
            return $value <= $now->day;
        }

        return $value <= $date->daysInMonth;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid day.';
    }

    protected function yearIsValid()
    {
        return $this->year && is_numeric($this->year) && $this->year > 0;
    }

    protected function monthIsValid()
    {
        return $this->month && is_numeric($this->month) && $this->month > 0;
    }
}