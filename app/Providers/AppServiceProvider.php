<?php

namespace App\Providers;

use App\Watermark;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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

        Validator::extend('slug', function ($attribute, $value) {
            return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)$/', $value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Watermark::class, function () {
            return new Watermark($this->app['config']['biologer.watermark']);
        });
    }
}
