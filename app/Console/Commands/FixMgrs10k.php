<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixMgrs10k extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:mgrs10k';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix MGRS10k fields by recalculating them from lat/long';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('observations')->orderBy('id')->chunk(1000, function ($chunk) {
            foreach ($chunk as $row) {
                $mgrs10k = mgrs10k($row->latitude, $row->longitude);

                if ($mgrs10k !== $row->mgrs10k) {
                    DB::table('observations')->where('id', $row->id)->update(['mgrs10k' => $mgrs10k]);
                }
            }
        });

        $this->info('Done!');
    }
}
