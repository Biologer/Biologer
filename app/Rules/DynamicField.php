<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DynamicField implements Rule
{
    /**
     * Available dynamic field to validate against.
     *
     * @var array
     */
    protected $availableDynamicFields;

    /**
     * Create a new rule instance.
     *
     * @param  array  $availableDynamicFields
     * @return void
     */
    public function __construct($availableDynamicFields)
    {
        $this->availableDynamicFields = $availableDynamicFields;
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
        if (!$this->validType($value)) {
            // TODO: Add message here
            return false;
        }

        if (!$this->validValue($value)) {
            // TODO: Add message here
            return false;
        }

        return true;
    }

    protected function validType($field)
    {
        return is_array($field) &&
            !empty($field['name']) &&
            in_array($field['name'], array_keys($this->availableDynamicFields)) &&
            class_exists($this->availableDynamicFields[$field['name']]);
    }

    protected function validValue($field)
    {
        $fieldClass = $this->availableDynamicFields[$field['name']];

        return !empty($field['value']) &&
            (new $fieldClass($field['name'], $field['value']))->validate();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
