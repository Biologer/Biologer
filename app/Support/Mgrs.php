<?php

namespace App\Support;

use PHPCoord\LatLng;
use PHPCoord\RefEll;
use PHPCoord\UTMRef;

class Mgrs
{
    const BLOCK_SIZE = 100000;
    const NORTHING_OFFSET = 10000000.0;
    const GRIDSQUARE_SET_COL_SIZE = 8;
    const GRIDSQUARE_SET_ROW_SIZE = 20;

    /**
     * Letters' groups for 100k easting.
     *
     * @var array
     */
    protected static $e100kLetters = ['ABCDEFGH', 'JKLMNPQR', 'STUVWXYZ'];

    /**
     * Letters' groups for 100k northing.
     *
     * @var array
     */
    protected static $n100kLetters = ['ABCDEFGHJKLMNPQRSTUV', 'FGHJKLMNPQRSTUVABCDE'];

    /**
     * @var UTMRef
     */
    protected $utm;

    /**
     * Create instance.
     *
     * @param  UTMRef  $utm
     */
    protected function __construct(UTMRef $utm)
    {
        $this->utm = $utm;
    }

    /**
     * Make instance by using latitude and longitude.
     *
     * @param  float  $latitude
     * @param  float  $longitude
     * @return self
     */
    public static function makeFromLatLong($latitude, $longitude)
    {
        $latLong = new LatLng($latitude, $longitude, 0, RefEll::wgs84());

        try {
            return new static($latLong->toUTMRef());
        } catch (\Exception $e) {
            return new NullMgrs;
        }
    }

    /**
     * Format to 10k precision.
     *
     * @return string
     */
    public function to10k()
    {
        return $this->convert(1);
    }

    /**
     * Convert UTM to MGRS with given precision.
     *
     * @param  int  $precision
     * @return string
     */
    public function convert($precision = 1)
    {
        return vsprintf('%s%s%s%s%s%s', [
            $this->zone(),
            $this->band(),
            $this->e100k(),
            $this->n100k(),
            $this->easting($precision),
            $this->northing($precision),
        ]);
    }

    /**
     * Zone number.
     *
     * @return int
     */
    protected function zone()
    {
        return (int) $this->utm->getLngZone();
    }

    /**
     * Zone letter.
     *
     * @return string
     */
    protected function band()
    {
        return $this->utm->getLatZone();
    }

    /**
     * UTM easting.
     *
     * @return int|float
     */
    protected function utmEasting()
    {
        return $this->utm->getX();
    }

    /**
     * UTM northing.
     *
     * @return int|float
     */
    protected function utmNorthing()
    {
        return $this->utm->getY();
    }

    /**
     * 100k letter for easting.
     *
     * @return string
     */
    public function e100k()
    {
        return static::$e100kLetters[($this->zone() - 1) % 3][$this->col() - 1];
    }

    /**
     * 100k letter for northing.
     *
     * @return string
     */
    public function n100k()
    {
        return static::$n100kLetters[($this->zone() - 1) % 2][$this->row()];
    }

    /**
     * Calculate column.
     *
     * @return int
     */
    protected function col()
    {
        return floor($this->utmEasting() / static::BLOCK_SIZE) % static::GRIDSQUARE_SET_COL_SIZE;
    }

    /**
     * Calculate row.
     *
     * @return int
     */
    protected function row()
    {
        return floor($this->utmNorthing() / static::BLOCK_SIZE) % static::GRIDSQUARE_SET_ROW_SIZE;
    }

    /**
     * MGRS easting with given precision.
     *
     * @param  int  $precision
     * @return string
     */
    protected function easting($precision)
    {
        return $this->applyPrecision($this->utmEasting(), $precision);
    }

    /**
     * MGRS northing with give precision.
     *
     * @param  int  $precision
     * @return string
     */
    protected function northing($precision)
    {
        return $this->applyPrecision($this->utmNorthing(), $precision);
    }

    /**
     * Modify given easting or northing to comply with format for given precision.
     *
     * @param  mixed  $value
     * @param  int  $precision
     * @return string
     */
    protected function applyPrecision($value, $precision)
    {
        $result = '';
        $val = floor((round($value) % self::BLOCK_SIZE) / pow(10, (5 - (int) $precision)));

        for ($i = strlen(strval($val)); $i < $precision; ++$i) {
            $result .= '0';
        }
        $result .= $val;

        return $result;
    }
}
