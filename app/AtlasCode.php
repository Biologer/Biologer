<?php

namespace App;

use Illuminate\Contracts\Support\Arrayable;

class AtlasCode implements Arrayable
{
    const CODES = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];

    /**
     * @var int
     */
    public $code;

    /**
     * Constructor.
     *
     * @param  int  $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Get all atlas codes.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function all()
    {
        return collect(self::CODES)->map(function ($code) {
            return new self($code);
        });
    }

    /**
     * Find instance by given code.
     *
     * @param  int  $code
     * @return self|null
     */
    public static function findByCode($code)
    {
        if (is_null($code)) {
            return;
        }

        return self::all()->first(function ($atlasCode) use ($code) {
            return $atlasCode->code === $code;
        });
    }

    /**
     * Get name translation.
     *
     * @return string
     */
    public function name()
    {
        return trans("atlasCodes.{$this->code}.name");
    }

    /**
     * Get description translation.
     *
     * @return string
     */
    public function description()
    {
        return trans("atlasCodes.{$this->code}.description");
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->code,
            'name' => $this->name(),
            'description' => $this->description(),
        ];
    }
}
