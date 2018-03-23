<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Fixes issue with MySQL indexed string column size.
        Schema::defaultStringLength(191);

        Collection::macro('latest', function () {
            return $this->sortByDesc('created_at');
        });

        Collection::macro('collect', function ($key, $default = null) {
            return new static($this->get($key, $default));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
