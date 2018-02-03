<?php

use App\Taxon;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('ancestry:rebuild', function () {
    $this->line('Rebuilding ancestry connections.');
    Taxon::rebuildAncestry();

    $this->line('Caching ancestry path.');
    Taxon::rebuildAncestryCache();

    $this->info('Finished rebuilding and caching ancestry.');
})->describe('Rebuild ancestry and cache ancestry path.');
