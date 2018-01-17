<?php

namespace App\Rules;

use App\FieldObservation;
use Illuminate\Contracts\Validation\Rule;

class ApprovableFieldObservation implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return FieldObservation::approvable()
            ->whereIn('id', array_wrap($value))
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid field observation/s';
    }
}
