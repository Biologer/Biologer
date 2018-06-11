<?php

namespace App\Importing;

use App\Import;
use Box\Spout\Common\Type;
use App\Jobs\ProcessImport;
use Illuminate\Support\Facades\DB;
use Box\Spout\Reader\ReaderFactory;
use Illuminate\Support\Facades\Storage;

abstract class BaseImport
{
    /**
     * Definition of all calumns with their labels.
     *
     * @return \Illuminate\Support\Collection
     */
    abstract public function allColumns();

    /**
     * List of all columns.
     *
     * @return array
     */
    public function columns()
    {
        return static::allColumns()->pluck('value');
    }

    /**
     * List of required columns.
     *
     * @return array
     */
    public function requiredColumns()
    {
        return static::allColumns()->filter->required->pluck('value');
    }

    /**
     * Queue import processing.
     *
     * @param  string  $path
     * @param  array  $columns
     * @return \App\Import
     */
    public function queue($path, array $columns)
    {
        $import = $this->createFromPath($path, $columns);

        ProcessImport::dispatch($import);

        return $import;
    }

    /**
     * Create new import model from file path and columns.
     *
     * @param  string  $path
     * @param  array  $columns
     * @return \App\Import
     */
    public function createFromPath($path, array $columns)
    {
        return Import::create([
            'type' => static::class,
            'columns' => $columns,
            'path' => $path,
            'user_id' => auth()->id(),
            'lang' => app()->getLocale(),
        ]);
    }

    /**
     * Parse the uploaded CSV into JSON collection.
     *
     * @param  \App\Import  $import
     * @return \App\Import
     */
    public function parse(Import $import)
    {
        // Update status to parsing.
        $import->updateStatusToParsing();

        Storage::disk('public')->put($import->parsedPath(), '');

        $writer = new JsonCollectionStreamWriter($import->parsedAbsolutePath());

        $this->read($import, function ($item) use ($writer) {
            $writer->add($item);
        });

        $writer->close();

        $import->updateStatusToParsed();

        return $import;
    }

    /**
     * Read uploaded CSV and parse the row.
     *
     * @param  \App\Import  $import
     * @param  callable  $callback
     * @return void
     */
    protected function read(Import $import, callable $callback)
    {
        $reader = ReaderFactory::create(Type::CSV);
        $reader->open($import->absolutePath());

        $mapper = new ColumnMapper($import);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $callback($mapper->map($row));
            }

            // Read only one sheet
            break;
        }

        $reader->close();
    }

    /**
     * Validate the parsed import.
     *
     * @param  \App\Import  $import
     * @return \App\Import
     */
    public function validate(Import $import)
    {
        if (! $import->status()->parsed()) {
            throw new \Exception('Import must be parsed before it can be validated!');
        }

        $import->updateStatusToValidating();

        $passed = true;
        $rowNumber = 1;

        $writer = new JsonCollectionStreamWriter($import->errorsAbsolutePath());

        $this->readParsed($import, function ($item) use ($writer, &$rowNumber, &$passed) {
            $validator = $this->makeValidator($item);

            if ($validator->fails()) {
                $passed = false;
            }

            foreach ($validator->errors()->all() as $error) {
                $writer->add([
                    'row' => $rowNumber,
                    'error' => $error,
                ]);
            }

            $rowNumber++;

            unset($validator); // Clear the validator instance from memory
        });

        $writer->close();

        $import->updateValidationStatus($passed);

        return $import;
    }

    /**
     * Read parsed import data.
     *
     * @param  \App\Import  $import
     * @param  callable  $callback
     * @return void
     */
    protected function readParsed(Import $import, callable $callback)
    {
        (new JsonCollectionStreamReader($import->parsedAbsolutePath()))->read($callback);
    }

    /**
     * Make validator instance.
     *
     * @param  array  $data
     * @return \Illuminate\Validation\Validator
     */
    abstract protected function makeValidator(array $data);

    /**
     * Store import in DB.
     *
     * @param  \App\Import  $import
     * @return void
     */
    public function store(Import $import)
    {
        if (! $import->status()->validationPassed()) {
            throw new \Exception('Cannot store this import! Either validation didn\'t pass or it has already been stored.');
        }

        $import->updateStatusToSaving();

        DB::beginTransaction();

        try {
            $this->readParsed($import, function ($item) use ($import) {
                $this->storeSingleItem($import, $item);
            });

            $import->updateStatusToSaved();
        } catch (\Exception $e) {
            DB::rollBack();

            $import->updateStatusToSavingFailed();
        }

        DB::commit();

        return $import;
    }

    /**
     * Store data from single CSV row.
     *
     * @param  \App\Import  $import
     * @param  array  $item
     * @return void
     */
    abstract protected function storeSingleItem(Import $import, array $item);
}
