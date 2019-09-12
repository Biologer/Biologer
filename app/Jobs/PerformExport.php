<?php

namespace App\Jobs;

use App\Export;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PerformExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Run the job 10 minutes before killing the process.
     *
     * @var int
     */
    public $timeout = 600;

    /**
     * @var \App\Export
     */
    public $export;

    /**
     * Create a new job instance.
     *
     * @param  \App\Export  $export
     * @return void
     */
    public function __construct(Export $export)
    {
        $this->export = $export;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->export->perform();
    }
}
