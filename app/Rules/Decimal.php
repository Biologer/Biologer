<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Decimal implements Rule
{
    private $options = [];
    private $message = '';

    /**
     * Create a new rule instance.
     *
     * @param  array  $options
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
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
        $min = $this->options['min'] ?? null;
        $max = $this->options['max'] ?? null;

        if (! $this->isFloat($value)) {
            $this->message = trans('validation.decimal', ['attribute' => $attribute]);

            return false;
        }

        $value = (float) str_replace(',', '.', $value);

        $smaller = $min && $value < $min;
        $larger = $max && $value > $max;

        if ($smaller && $larger) {
            $this->message = trans('validation.between.numeric', [
                'attribute' => $attribute,
                'min' => $min,
                'max' => $max,
            ]);

            return false;
        }

        if ($smaller) {
            $this->message = trans('validation.min.numeric', [
                'attribute' => $attribute,
                'min' => $min,
            ]);

            return false;
        }

        if ($larger) {
            $this->message = trans('validation.max.numeric', [
                'attribute' => $attribute,
                'max' => $max,
            ]);

            return false;
        }

        return true;
    }

    private function isFloat($value)
    {
        return preg_match('/^[-+]?([0-9]+([\.|\,][0-9]+)?|[\.|\,][0-9]+)$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
