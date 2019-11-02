<?php

namespace App\Console\Commands;

use App\Import;
use Illuminate\Console\Command;

class ClearImports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imports:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old imports';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->warn('Clearing old imports...');

        Import::where('created_at', '<=', now()->subWeek(1))->each(function ($import) {
            $import->delete();
        });

        $this->info('Finished clearing old imports!');
    }
}
