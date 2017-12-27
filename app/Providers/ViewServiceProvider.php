<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('role', function ($roles) {
            return auth()->user()->hasAnyRole(array_wrap($roles));
        });

        Blade::if('roles', function ($roles) {
            return auth()->user()->hasAllRole(array_wrap($roles));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
