<?php

namespace App\Exports;

use MyCLabs\Enum\Enum;

class ExportStatus extends Enum
{
    const QUEUED = 'queued';
    const EXPORTING = 'exporting';
    const FAILED = 'failed';
    const FINISHED = 'finished';

    /**
     * Statuses that mean the export is in progress.
     *
     * @return array
     */
    public static function inProgress()
    {
        return [static::QUEUED, static::EXPORTING];
    }
}
