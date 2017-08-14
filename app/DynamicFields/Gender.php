<?php

namespace App\DynamicFields;

class Gender extends DynamicField
{
    /**
     * Options for select fields.
     *
     * @return array
     */
    public function options()
    {
        return [
            ['value' => 'female', 'text' => 'Female'],
            ['value' => 'male', 'text' => 'Male'],
        ];
    }

    /**
     * Default value.
     *
     * @return mixed
     */
    public function default()
    {
        return $this->options()[0]['value'];
    }

    /**
     * Label.
     *
     * @return string
     */
    public function label()
    {
        return 'Gender';
    }

    /**
     * Input type.
     *
     * @return string
     */
    public function type()
    {
        return static::TYPE_SELECT;
    }

    /**
     * Validation for field's value.
     *
     * @return bool
     */
    public function validate()
    {
        return collect($this->options())->pluck('value')
            ->contains($this->value);
    }
}
