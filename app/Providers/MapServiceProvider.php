<?php

namespace App\Providers;

use App\Maps\BasicMgrs10kMap;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class MapServiceProvider extends ServiceProvider implements DeferrableProvider
{
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
            $territory = strtolower($this->app['config']['biologer.territory']);

            return BasicMgrs10kMap::fromPath(resource_path("maps/mgrs10k/{$territory}.svg"));
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
