<?php

namespace App;

use Illuminate\Contracts\Support\Arrayable;

class CollectionSpecimenDisposition implements Arrayable
{
    const IN_COLLECTION = 'in_collection';
    const MISSING = 'missing';
    const VOUCHER_ELSEWHERE = 'voucher_elsewhere';
    const DUPLICATE_ELSEWHERE = 'duplicate_elswhere';

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
        return collect([
            new static(self::IN_COLLECTION),
            new static(self::MISSING),
            new static(self::VOUCHER_ELSEWHERE),
            new static(self::DUPLICATE_ELSEWHERE),
        ]);
    }

    /**
     * List of available licenses.
     *
     * @return array
     */
    public static function options()
    {
        return self::all()->mapWithKeys(function ($license) {
            return [$license->value => $license->label()];
        })->all();
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
        return trans('collection_specimen_disposition.'.$this->value);
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
