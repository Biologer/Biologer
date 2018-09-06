<?php

namespace App\Console\Commands;

use App\Support\Localization;

use Illuminate\Console\Command;

class ClearLocalizationCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'localization:cache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cached localization strings used by the frontend.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->warn('Clearing localization strings cache...');

        Localization::clearCache();

        $this->info('Finished clearing localization strings cache!');
    }
}
