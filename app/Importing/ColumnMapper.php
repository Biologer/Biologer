<?php

namespace App\Importing;

use App\Import;

class ColumnMapper
{
    /**
     * @var \App\Import
     */
    protected $import;

    /**
     * @param  \App\Import  $import
     */
    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    /**
     * Map row data to columns.
     *
     * @param  array  $row
     * @return array
     */
    public function map(array $row)
    {
        $mapped = [];

        foreach ($this->import->columns as $index => $column) {
            $mapped[$column] = $row[$index] ?? null;
        }

        return $mapped;
    }
}
