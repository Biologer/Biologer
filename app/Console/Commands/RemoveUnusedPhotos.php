<?php

namespace App\Console\Commands;

use App\Photo;

use Illuminate\Console\Command;

class RemoveUnusedPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove photos that are no longer used.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->warn('Removing unused photos...');

        Photo::doesntHave('fieldObservations')->olderThanDay()->get()->each->delete();

        $this->info('Unused photos have been removed!');
    }
}
