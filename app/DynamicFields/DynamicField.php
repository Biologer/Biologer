<?php

namespace App\DynamicFields;

class DynamicField
{
    const TYPE_SELECT = 'select';
    const TYPE_TEXT = 'text';

    /**
     * Field value
     *
     * @var mixed
     */
    protected $value;

    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * Get field class from name.
     *
     * @param  string  $name
     * @return string
     */
    public static function classFromName($name)
    {
        return 'App\DynamicFields\\'.ucfirst(camel_case($name));
    }

    /**
     * Get the name for the field.
     *
     * @param  string  $class
     * @return string
     */
    public static function classToName($class)
    {
        return snake_case(class_basename($class));
    }

    /**
     * Label.
     *
     * @return string
     */
    public function label()
    {
        return 'Label';
    }

    /**
     * Options for select fields.
     *
     * @return array
     */
    public function options()
    {
        return [];
    }

    /**
     * Placeholder.
     *
     * @return string
     */
    public function placeholder()
    {
        return '';
    }

    /**
     * Default value.
     *
     * @return mixed
     */
    public function default()
    {
        return null;
    }

    /**
     * Input type.
     *
     * @return string
     */
    public function type()
    {
        return static::TYPE_TEXT;
    }

    /**
     * Validation for field's value.
     *
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * Array of field data.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => static::classToName(static::class),
            'value' => $this->value,
            'label' => $this->label(),
            'placeholder' => $this->placeholder(),
            'default' => $this->default(),
            'type' => $this->type(),
            'options' => $this->options(),
        ];
    }

    /**
     * Field data encoded to JSON.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
