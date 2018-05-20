<?php

namespace App\Providers;

use App\Watermark;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
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

        Paginator::defaultView('pagination::bulma');
        Paginator::defaultSimpleView('pagination::bulma');
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
