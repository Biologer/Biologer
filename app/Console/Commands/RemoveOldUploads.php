<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class RemoveOldUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uploads:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old uploads to free up disk space.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->warn('Removing old uploaded files...');

        Storage::disk('public')->delete(array_filter(
            Storage::disk('public')->allFiles('uploads'),
            [$this, 'fileShouldBeRemoved']
        ));

        $this->info('Old uploaded files have been removed!');
    }

    /**
     * Check if file should be removed.
     *
     * @param  string  $file
     * @return bool
     */
    protected function fileShouldBeRemoved($file)
    {
        return Storage::disk('public')->lastModified($file) < Carbon::yesterday()->getTimestamp();
    }
}
