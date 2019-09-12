<?php

namespace App\Jobs;

use App\Import;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Run the job 10 minutes before killing the process.
     *
     * @var int
     */
    public $timeout = 600;

    /**
     * @var \App\Import
     */
    public $import;

    /**
     * Create a new job instance.
     *
     * @param  \App\Import  $import
     * @return void
     */
    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->import->refresh()->status()->cancelled()) {
            return;
        }

        // Resolve the importer based on the type stored in the import entity.
        $importer = $this->import->makeImporter()->parse()->validate();

        if ($this->import->status()->validationPassed()) {
            $importer->store();
        }
    }
}
