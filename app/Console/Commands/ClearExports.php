<?php

namespace App\Console\Commands;

use App\Export;
use Illuminate\Console\Command;

class ClearExports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exports:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old exports';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->warn('Clearing old exports...');

        Export::where('created_at', '<=', now()->subDay(1))->each(function ($export) {
            $export->delete();
        });

        $this->info('Finished clearing old exports!');
    }
}
