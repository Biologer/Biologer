<?php

namespace App;

class Sex
{
    const MALE = 'male';
    const FEMALE = 'female';

    /**
     * Labels for Validity options.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function options()
    {
        return collect([
            self::MALE => __('labels.sexes.male'),
            self::FEMALE => __('labels.sexes.female'),
        ]);
    }

    /**
     * Get labels for sexes.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function labels()
    {
        return self::options()->values();
    }

    /**
     * Get value based on label.
     *
     * @param  string  $label
     * @return void
     */
    public static function getValueFromLabel($label)
    {
        return self::options()->flip()->get($label);
    }
}
