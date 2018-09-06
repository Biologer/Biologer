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
     * Import model instance.
     *
     * @var \App\Import
     */
    private $import;

    /**
     * Construct importer.
     *
     * @param  \App\Import  $import
     */
    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    /**
     * Get import model instance.
     *
     * @return \App\Import
     */
    public function model()
    {
        return $this->import;
    }

    /**
     * Definition of all calumns with their labels.
     *
     * @param  \App\User|null  $user
     * @return \Illuminate\Support\Collection
     */
    abstract static public function columns($user = null);

    /**
     * List of all columns.
     *
     * @param  \App\User|null  $user
     * @return array
     */
    public static function availableColumns($user = null)
    {
        return static::columns($user)->pluck('value');
    }

    /**
     * List of required columns.
     *
     * @param  \App\User|null  $user
     * @return array
     */
    public static function requiredColumns($user = null)
    {
        return static::columns($user)->filter->required->pluck('value');
    }

    /**
     * Queue import processing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Import
     */
    public static function fromRequest($request)
    {
        return tap(static::createFromRequest($request), function ($import) {
            ProcessImport::dispatch($import);
        });
    }

    /**
     * Create new import using data from request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Import
     */
    protected static function createFromRequest($request)
    {
        return Import::create([
            'type' => static::class,
            'columns' => $request->input('columns', []),
            'path' => $request->file('file')->store('imports'),
            'user_id' => $request->user()->id,
            'lang' => app()->getLocale(),
            'has_heading' => $request->input('has_heading', false),
        ]);
    }

    /**
     * Parse the uploaded CSV into JSON collection.
     *
     * @return $this
     */
    public function parse()
    {
        // Update status to parsing.
        $this->model()->updateStatusToParsing();

        Storage::put($this->model()->parsedPath(), '');

        $writer = new JsonCollectionStreamWriter($this->model()->parsedAbsolutePath());

        $this->read(function ($item) use ($writer) {
            $writer->add($item);
        });

        $writer->close();

        $this->model()->updateStatusToParsed();

        return $this;
    }

    /**
     * Read uploaded CSV and parse the row.
     *
     * @param  callable  $callback
     * @return void
     */
    protected function read(callable $callback)
    {
        $reader = ReaderFactory::create(Type::CSV);
        $reader->open($this->model()->absolutePath());

        $mapper = new ColumnMapper($this->model()->columns);

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
     * @return $this
     */
    public function validate()
    {
        if (! $this->model()->status()->parsed()) {
            throw new \Exception('Import must be parsed before it can be validated!');
        }

        $this->model()->updateStatusToValidating();

        $passed = true;
        $rowNumber = 1;

        $writer = new JsonCollectionStreamWriter($this->model()->errorsAbsolutePath());

        $this->readParsed(function ($item) use ($writer, &$rowNumber, &$passed) {
            $validator = $this->setValidatorLocale($this->makeValidator($item));

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

        $this->model()->updateValidationStatus($passed);

        return $this;
    }

    /**
     * Read parsed import data.
     *
     * @param  callable  $callback
     * @return void
     */
    protected function readParsed(callable $callback)
    {
        (new JsonCollectionStreamReader($this->model()->parsedAbsolutePath()))->read($callback);
    }

    /**
     * Make validator instance.
     *
     * @param  array  $data
     * @return \Illuminate\Validation\Validator
     */
    abstract protected function makeValidator(array $data);

    /**
     * Set validator locale to use language set for import.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return \Illuminate\Validation\Validator
     */
    protected function setValidatorLocale($validator)
    {
        $validator->getTranslator()->setLocale($this->import->lang);

        return $validator;
    }

    /**
     * Store import in DB.
     *
     * @return void
     */
    public function store()
    {
        if (! $this->model()->status()->validationPassed()) {
            throw new \Exception('Cannot store this import! Either validation didn\'t pass or it has already been stored.');
        }

        $this->model()->updateStatusToSaving();

        DB::beginTransaction();

        try {
            $this->readParsed(function ($item) {
                $this->storeSingleItem($item);
            });

            $this->model()->updateStatusToSaved();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->model()->updateStatusToSavingFailed();
        }

        DB::commit();

        return $this->import;
    }

    /**
     * Store data from single CSV row.
     *
     * @param  array  $item
     * @return void
     */
    abstract protected function storeSingleItem(array $item);
}
