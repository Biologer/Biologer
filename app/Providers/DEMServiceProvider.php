<?php

namespace App\Providers;

use App\DEM\RunalyzeReader;
use App\DEM\MemoizingReader;
use App\DEM\ReaderInterface;
use Illuminate\Support\ServiceProvider;
use Runalyze\DEM\Provider\GeoTIFF\SRTM4Provider;

class DEMServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ReaderInterface::class, function ($app) {
            return new MemoizingReader(new RunalyzeReader(
                new SRTM4Provider($app['config']->get('biologer.srtm_path'))
            ));
        });
    }
}
