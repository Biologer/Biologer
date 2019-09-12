<?php

namespace App\Providers;

use App\DEM\MemoizingReader;
use App\DEM\Reader;
use App\DEM\RunalyzeReader;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Runalyze\DEM\Provider\GeoTIFF\SRTM4Provider;

class DEMServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Reader::class, function ($app) {
            return new MemoizingReader(new RunalyzeReader(
                new SRTM4Provider($app['config']->get('biologer.srtm_path'))
            ));
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
            Reader::class,
        ];
    }
}
