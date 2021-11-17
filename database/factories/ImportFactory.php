<?php

namespace Database\Factories;

use App\Import;
use App\Importing\FieldObservationImport;
use App\Importing\ImportStatus;
use App\Importing\LiteratureObservationImport;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Import::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => ImportStatus::PROCESSING_QUEUED,
            'type' => FieldObservationImport::class,
            'columns' => FieldObservationImport::requiredColumns(),
            'lang' => app()->getLocale(),
        ];
    }

    /**
     * @return static
     */
    public function fieldObservation()
    {
        return $this->state([
            'type' => FieldObservationImport::class,
        ]);
    }

    /**
     * @return static
     */
    public function literatureObservation()
    {
        return $this->state([
            'type' => LiteratureObservationImport::class,
        ]);
    }

    /**
     * @return static
     */
    public function processingQueued()
    {
        return $this->state([
            'status' => ImportStatus::PROCESSING_QUEUED,
        ]);
    }

    /**
     * @return static
     */
    public function parsing()
    {
        return $this->state([
            'status' => ImportStatus::PARSING,
        ]);
    }

    /**
     * @return static
     */
    public function parsed()
    {
        return $this->state([
            'status' => ImportStatus::PARSED,
        ]);
    }

    /**
     * @return static
     */
    public function validating()
    {
        return $this->state([
            'status' => ImportStatus::VALIDATING,
        ]);
    }

    /**
     * @return static
     */
    public function validationFailed()
    {
        return $this->state([
            'status' => ImportStatus::VALIDATION_FAILED,
        ]);
    }

    /**
     * @return static
     */
    public function validationPassed()
    {
        return $this->state([
            'status' => ImportStatus::VALIDATION_PASSED,
        ]);
    }

    /**
     * @return static
     */
    public function saving()
    {
        return $this->state([
            'status' => ImportStatus::SAVING,
        ]);
    }

    /**
     * @return static
     */
    public function savingFailed()
    {
        return $this->state([
            'status' => ImportStatus::SAVING_FAILED,
        ]);
    }

    /**
     * @return static
     */
    public function saved()
    {
        return $this->state([
            'status' => ImportStatus::SAVED,
        ]);
    }
}
