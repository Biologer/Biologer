<?php

namespace App\Providers;

use App\Importing\BaseImport;
use Illuminate\Support\ServiceProvider;
use App\Importing\FieldObservationImport;
use App\Http\Controllers\Api\FieldObservationImportsController;

/**
 * This is the place to register bindings to specific implementation
 * of import and the condition when it should be resolved.
 */
class ImportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(FieldObservationImportsController::class)
            ->needs(BaseImport::class)
            ->give(function () {
                return $this->app->make(FieldObservationImport::class);
            });
    }
}
