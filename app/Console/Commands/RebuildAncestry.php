<?php

namespace App\Console\Commands;

use App\Taxon;
use Illuminate\Console\Command;

class RebuildAncestry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ancestry:rebuild';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild ancestry.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->warn('Rebuilding ancestry connections...');
        Taxon::rebuildAncestry();

        $this->info('Finished rebuilding ancestry!');
    }
}
