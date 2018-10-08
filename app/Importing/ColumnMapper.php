<?php

namespace App\Importing;

class ColumnMapper
{
    /**
     * @var array
     */
    protected $columns;

    /**
     * @param  array  $columns
     */
    public function __construct(array $columns)
    {
        $this->columns = $columns;
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

        foreach ($this->columns as $index => $column) {
            $mapped[$column] = $row[$index] ?? null;
        }

        return $mapped;
    }
}
