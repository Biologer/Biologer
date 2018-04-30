<?php

namespace App\Providers;

use App\Maps\BasicMgrs10kMap;
use Illuminate\Support\ServiceProvider;

class MapServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('map.mgrs10k.basic', function () {
            return new BasicMgrs10kMap($this->app['config']['biologer.territory']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'map.mgrs10k.basic',
        ];
    }
}
