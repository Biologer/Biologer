<?php

namespace App\Console\Commands;

use App\Support\Localization;
use Illuminate\Console\Command;

class CacheLocalization extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'localization:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache localization strings used by the frontend.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->warn('Clearing localization strings cache...');

        Localization::clearCache();

        $this->warn('Caching localization strings...');

        Localization::cache();

        $this->info('Localization strings are cached!');
    }
}
