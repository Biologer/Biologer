<?php

namespace App\Providers;

use App\Rules\Contain;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('slug', function ($attribute, $value) {
            return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)$/', $value);
        });

        Validator::extend('contain', function ($attribute, $value, $parameters, $validator) {
            if (! $validator->hasRule($attribute, 'Array') || ! is_array($value)) {
                return false;
            }

            foreach ($value as $element) {
                if (is_array($element)) {
                    return false;
                }
            }

            return count(array_diff($parameters, $value)) == 0;
        });

        Validator::extend('contains_non_empty', function ($attribute, $value, $parameters, $validator) {
            if (! $validator->hasRule($attribute, 'Array') || ! is_array($value)) {
                return false;
            }

            foreach ($value as $element) {
                if (! empty($element)) {
                    return true;
                }
            }

            return false;
        });

        Rule::macro('contain', function ($values) {
            if ($values instanceof Collection) {
                $values = $values->toArray();
            }

            return new Contain(is_array($values) ? $values : func_get_args());
        });
    }
}
