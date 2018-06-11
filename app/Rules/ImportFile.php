<?php

namespace App\Rules;

use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Illuminate\Contracts\Validation\Rule;

class ImportFile implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $reader = ReaderFactory::create(Type::CSV); // for CSV files

        $reader->open($value->getPathname());

        $hasOneRow = false;

        // Check that we have at least one row
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $hasOneRow = true;
                break;
            }

            break;
        }

        $reader->close();

        return $hasOneRow;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validate.csv_row_count');
    }
}
