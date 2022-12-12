<?php

namespace App;

use Illuminate\Contracts\Support\Arrayable;

class CollectionSpecimenDisposition implements Arrayable
{
    const IN_COLLECTION = 'in_collection';
    const MISSING = 'missing';
    const VOUCHER_ELSEWHERE = 'voucher_elsewhere';
    const DUPLICATE_ELSEWHERE = 'duplicate_elswhere';

    const ALL = [
        self::IN_COLLECTION,
        self::MISSING,
        self::VOUCHER_ELSEWHERE,
        self::DUPLICATE_ELSEWHERE,
    ];

    /**
     * @var string
     */
    public $value;

    /**
     * Constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Get all licenses.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function all()
    {
        return collect(self::ALL)->map(function ($value) {
            return new static($value);
        });
    }

    /**
     * List of available licenses.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function options()
    {
        return self::all()->mapWithKeys(function ($license) {
            return [$license->value => $license->label()];
        });
    }

    /**
     * Get license IDs.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function values()
    {
        return self::all()->pluck('value');
    }

    /**
     * Get ID of the first license.
     *
     * @return int
     */
    public static function firstValue()
    {
        return self::values()->first();
    }

    /**
     * Get first license info.
     *
     * @return self
     */
    public static function first()
    {
        return self::all()->first();
    }

    /**
     * Find license by given ID.
     *
     * @param  string  $value
     * @return self|null
     */
    public static function findByValue($value)
    {
        return self::all()->where('value', $value)->first();
    }

    /**
     * Get name translation.
     *
     * @return string
     */
    public function label()
    {
        return trans('specimenDisposition.'.$this->value);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
        ];
    }
}
