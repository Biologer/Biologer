<?php

namespace App\Providers;

use App\DynamicFields\DynamicField;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->dynamicFiedsValidation();
    }

    /**
     * Rules for validating dynamic fields.
     *
     * @return void
     */
    protected function dynamicFiedsValidation()
    {
        Validator::extend('df_supported', function ($attribute, $value, $parameters, $validator) {
            $field = array_last(explode('.', $attribute));

            return class_exists(DynamicField::classFromName($field)) && in_array($field, $parameters);
        });

        Validator::extend('df_valid', function ($attribute, $value, $parameters, $validator) {
            $field = DynamicField::classFromName(array_last(explode('.', $attribute)));

            return (new $field($value))->validate();
        });
    }
}
