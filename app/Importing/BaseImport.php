<?php

namespace App\Importing;

use App\Import;
use Box\Spout\Common\Type;
use App\Jobs\ProcessImport;
use Illuminate\Support\Facades\DB;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Reader\SheetInterface;
use Box\Spout\Reader\ReaderInterface;
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
    abstract public static function columns($user = null);

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
        $user = $request->user();

        return Import::create([
            'type' => static::class,
            'columns' => $request->input('columns', []),
            'path' => $request->file('file')->store('imports'),
            'user_id' => $user->id,
            'for_user_id' => $user->hasAnyRole(['admin', 'curator']) ? $request->input('user_id') : null,
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
        if ($this->import->refresh()->status()->cancelled()) {
            return $this;
        }

        // Update status to parsing.
        $this->import->updateStatusToParsing();

        $this->readImportAndWriteParsed();

        $this->import->updateStatusToParsed();

        return $this;
    }

    /**
     * Read import and write JSON collection of parsed rows.
     *
     * @return void
     */
    private function readImportAndWriteParsed()
    {
        // Ensure the file exists.
        Storage::put($this->import->parsedPath(), '');

        $writer = $this->makeParsedWriter();

        $this->read(function ($item) use ($writer) {
            $writer->add($item);
        });

        $writer->close();
    }

    /**
     * Make instance of JSON collection writer for parsed data.
     *
     * @return \App\Importing\JsonCollectionStreamWriter
     */
    private function makeParsedWriter()
    {
        return new JsonCollectionStreamWriter($this->import->parsedAbsolutePath());
    }

    /**
     * Read uploaded CSV and parse the row.
     *
     * @param  callable  $callback
     * @return void
     */
    protected function read(callable $callback)
    {
        $reader = $this->makeImportReader();

        $this->readSpreadsheet($reader, $this->makeColumnMapper(), $callback);

        $reader->close();
    }

    /**
     * Make reader instance for imported spreadsheet file.
     *
     * @return \Box\Spout\Reader\ReaderInterface
     */
    private function makeImportReader()
    {
        $reader = ReaderFactory::create(Type::CSV);

        $reader->open($this->import->absolutePath());

        return $reader;
    }

    /**
     * Make instance of collumn mapper.
     *
     * @return \App\Importing\ColumnMapper
     */
    private function makeColumnMapper()
    {
        return new ColumnMapper($this->import->columns);
    }

    /**
     * Read rows from import file.
     *
     * @param  \Box\Spout\Reader\ReaderInterface  $reader
     * @param  \App\Importing\ColumnMapper  $mapper
     * @param  callable  $callback
     * @return void
     */
    private function readSpreadsheet(ReaderInterface $reader, ColumnMapper $mapper, callable $callback)
    {
        foreach ($reader->getSheetIterator() as $sheet) {
            $this->readSingleSheet($sheet, $mapper, $callback);

            // We don't support importing from multiple sheets, so break after the first one.
            break;
        }
    }

    /**
     * Read single sheed from import file.
     *
     * @param  \Box\Spout\Reader\SheetInterface  $sheet
     * @param  \App\Importing\ColumnMapper  $mapper
     * @param  callable  $callback
     * @return void
     */
    private function readSingleSheet(SheetInterface $sheet, ColumnMapper $mapper, callable $callback)
    {
        $firstRow = true;

        foreach ($sheet->getRowIterator() as $row) {
            if ($firstRow && $this->import->has_heading) {
                $firstRow = false;

                continue;
            }

            $callback($mapper->map($row));
        }
    }

    /**
     * Validate the parsed import.
     *
     * @return $this
     */
    public function validate()
    {
        if ($this->import->refresh()->status()->cancelled()) {
            return $this;
        }

        if (! $this->import->status()->parsed()) {
            throw new \Exception('Import must be parsed before it can be validated!');
        }

        $this->import->updateStatusToValidating();

        $this->import->updateValidationStatus($this->validateParsedAndWriteErrors());

        return $this;
    }

    /**
     * Validate parsed data to check if there are any errors in the provided data.
     * Validation errors are writen in a json collection.
     *
     * @return bool
     */
    private function validateParsedAndWriteErrors()
    {
        $passed = true;
        $rowNumber = 1;

        $writer = $this->makeValidationErrorWriter();

        $this->readParsed(function ($item) use ($writer, &$rowNumber, &$passed) {
            if (! $this->validateItemAndWriteErrors($item, $writer, $rowNumber)) {
                $passed = false;
            }

            $rowNumber++;
        });

        $writer->close();

        return $passed;
    }

    /**
     * Make new instance of errors collection writer.
     *
     * @return \App\Importing\JsonCollectionStreamWriter
     */
    private function makeValidationErrorWriter()
    {
        return new JsonCollectionStreamWriter($this->import->errorsAbsolutePath());
    }

    /**
     * Read parsed import data.
     *
     * @param  callable  $callback
     * @return void
     */
    protected function readParsed(callable $callback)
    {
        (new JsonCollectionStreamReader($this->import->parsedAbsolutePath()))->read($callback);
    }

    /**
     * Validate one parsed collection item.
     *
     * @param  array  $item
     * @param  \App\Importing\JsonCollectionStreamWriter  $writer
     * @param  int  $rowNumber
     * @return bool
     */
    private function validateItemAndWriteErrors($item, $writer, $rowNumber)
    {
        $validator = $this->setValidatorLocale($this->makeValidator($item));

        $passed = $validator->passes();

        $this->writeValidatorErrorsForRow($validator, $writer, $rowNumber);

        unset($validator); // Clear the validator instance from memory

        return $passed;
    }

    /**
     * Write validator errors for a row.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @param  \App\Importing\JsonCollectionStreamWriter  $writer
     * @param  int  $rowNumber
     * @return void
     */
    private function writeValidatorErrorsForRow($validator, $writer, $rowNumber)
    {
        foreach ($validator->errors()->all() as $error) {
            $writer->add([
                'row' => $rowNumber,
                'error' => $error,
            ]);
        }
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
        if ($this->import->refresh()->status()->cancelled()) {
            return $this->import;
        }

        if (! $this->import->status()->validationPassed()) {
            throw new \Exception('Cannot store this import! Either validation didn\'t pass or it has already been stored.');
        }

        $this->import->updateStatusToSaving();

        $this->storeParsed();

        return $this->import;
    }

    /**
     * Store parsed data in DB.
     *
     * @return void
     */
    private function storeParsed()
    {
        DB::beginTransaction();

        try {
            $this->readParsed(function ($item) {
                $this->storeSingleItem($item);
            });

            $this->import->updateStatusToSaved();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->import->updateStatusToSavingFailed();
        }

        DB::commit();
    }

    /**
     * Store data from single CSV row.
     *
     * @param  array  $item
     * @return void
     */
    abstract protected function storeSingleItem(array $item);
}
