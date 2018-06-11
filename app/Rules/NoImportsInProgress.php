<?php

namespace App\Rules;

use App\Import;
use Illuminate\Contracts\Validation\Rule;

class NoImportsInProgress implements Rule
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
        return ! Import::submittedBy(auth()->user())->inProgress()->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('imports.has_in_progress');
    }
}
