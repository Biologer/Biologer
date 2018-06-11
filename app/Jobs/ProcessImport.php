<?php

namespace App\Jobs;

use App\Import;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
        // Set locale from import so we have translated validation messages.
        app()->setLocale($this->import->lang);

        // Resolve the importer based on the type stored in the import entity.
        $importer = app($this->import->type);

        $importer->parse($this->import);
        $importer->validate($this->import);

        if ($this->import->status()->validationPassed()) {
            $importer->store($this->import);
        }
    }
}
