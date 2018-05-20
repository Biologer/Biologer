<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
    }
}
