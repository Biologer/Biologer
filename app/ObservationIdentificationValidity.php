<?php

namespace App;

use MyCLabs\Enum\Enum;

class ObservationIdentificationValidity extends Enum
{
    const INVALID = 0;
    const VALID = 1;
    const SYNONYM = 2;

    /**
     * @return array
     */
    public static function darwinCore()
    {
        return [
            self::INVALID => 'misapplied',
            self::VALID => 'valid',
            self::SYNONYM => 'synonym',
        ];
    }

    /**
     * Labels for Validity options.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function options()
    {
        return collect([
            self::INVALID => __('labels.literature_observations.validity.invalid'),
            self::VALID => __('labels.literature_observations.validity.valid'),
            self::SYNONYM => __('labels.literature_observations.validity.synonym'),
        ]);
    }

    /**
     * Get translation for the identification validity.
     *
     * @return string
     */
    public function translation()
    {
        return self::options()[$this->value];
    }

    /**
     * Convert to value for Darwin Core.
     *
     * @return string
     */
    public function toDarwinCore()
    {
        return self::darwinCore()[$this->value];
    }
}
