<?php

namespace App\Console\Commands;

use App\Photo;
use App\License;
use Illuminate\Console\Command;

class WatermarkPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:watermark {--all : Watermark all photos, including the ones already watermarked}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watermark photos that are licensed to be shown only with watermark';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->warn('Starting to watermark photos that require it...');

        Photo::where('license', License::PARTIALLY_OPEN)->chunk(100, function ($photos) {
            $photos->each(function ($photo) {
                if (! $this->option('all') && $photo->alreadyWatermarked()) {
                    return;
                }

                $this->info('Watermarking photo with ID: '.$photo->id);

                $photo->watermark();
            });
        });

        $this->info('Finished with watermarking photos.');
    }
}
