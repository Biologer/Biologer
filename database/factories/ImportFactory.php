<?php

use App\Import;
use Faker\Generator as Faker;
use App\Importing\ImportStatus;
use App\Importing\FieldObservationImport;

$factory->define(Import::class, function (Faker $faker) {
    return [
        'status' => ImportStatus::PROCESSING_QUEUED,
        'type' => FieldObservationImport::class,
        'columns' => (new FieldObservationImport)->requiredColumns(),
        'lang' => app()->getLocale(),
    ];
});

$factory->state(Import::class, 'fieldObservation', [
    'type' => FieldObservationImport::class,
]);

$factory->state(Import::class, 'processingQueued', [
    'status' => ImportStatus::PROCESSING_QUEUED,
]);

$factory->state(Import::class, 'parsing', [
    'status' => ImportStatus::PARSING,
]);

$factory->state(Import::class, 'parsed', [
    'status' => ImportStatus::PARSING,
]);

$factory->state(Import::class, 'validating', [
    'status' => ImportStatus::VALIDATING,
]);

$factory->state(Import::class, 'validationFailed', [
    'status' => ImportStatus::VALIDATION_FAILED,
]);

$factory->state(Import::class, 'validationPassed', [
    'status' => ImportStatus::VALIDATION_PASSED,
]);

$factory->state(Import::class, 'saving', [
    'status' => ImportStatus::SAVING,
]);

$factory->state(Import::class, 'savingFailed', [
    'status' => ImportStatus::SAVING_FAILED,
]);

$factory->state(Import::class, 'saved', [
    'status' => ImportStatus::SAVED,
]);
