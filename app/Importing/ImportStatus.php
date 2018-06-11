<?php

namespace App\Importing;

class ImportStatus extends \MyCLabs\Enum\Enum
{
    const PROCESSING_QUEUED = 'processing_queued';
    const PARSING = 'parsing';
    const PARSED = 'parsed';
    const VALIDATING = 'validating';
    const VALIDATION_FAILED = 'validation_failed';
    const VALIDATION_PASSED = 'validation_passed';
    const SAVING = 'saving';
    const SAVING_FAILED = 'saving_failed';
    const SAVED = 'saved';

    /**
     * Check if import status is "parsed".
     *
     * @return bool
     */
    public function parsed()
    {
        return $this->getValue() === static::PARSED;
    }

    /**
     * Check if import status is "validation_failed".
     *
     * @return bool
     */
    public function validationFailed()
    {
        return $this->getValue() === static::VALIDATION_FAILED;
    }

    /**
     * Check if import status is "validation_passed".
     *
     * @return bool
     */
    public function validationPassed()
    {
        return $this->getValue() === static::VALIDATION_PASSED;
    }

    /**
     * Check if import status is "stored".
     *
     * @return bool
     */
    public function saved()
    {
        return $this->getValue() === static::SAVED;
    }

    /**
     * Statuses that mark import as done with processing:
     * "validation_failed" & "stored".
     *
     * @return array
     */
    public static function doneStatuses()
    {
        return [
            static::VALIDATION_FAILED,
            static::SAVING_FAILED,
            static::SAVED,
        ];
    }
}
