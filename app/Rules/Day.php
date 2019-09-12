<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class Day implements Rule
{
    /**
     * @var int|string
     */
    protected $year;

    /**
     * @var int|string
     */
    protected $month;

    /**
     * Create a new rule instance.
     *
     * @param  int|string  $year
     * @param  int|string  $month
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
        if (! $this->yearIsValid() || ! $this->monthIsValid() || $value <= 0) {
            return false;
        }

        $now = Carbon::now();
        $date = Carbon::create($this->year, $this->month);

        if ($now->year == $this->year && $now->month == $this->month) {
            return $value <= $now->day + 1;
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
        return trans('validation.day');
    }

    /**
     * Check if year is valid.
     *
     * @return bool
     */
    protected function yearIsValid()
    {
        return $this->year && is_numeric($this->year) && $this->year > 0;
    }

    /**
     * Check if month is valid.
     *
     * @return bool
     */
    protected function monthIsValid()
    {
        return $this->month && is_numeric($this->month) && $this->month > 0;
    }
}
