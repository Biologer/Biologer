<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class Exif
{
    /**
     * Raw EXIF data.
     *
     * @var array|null
     */
    protected $exif;

    public function __construct($exif)
    {
        $this->exif = $exif;
    }

    /**
     * Format EXIF data into format that we can use.
     *
     * @return array|null
     */
    public function format()
    {
        if (empty($this->exif)) {
            return;
        }

        return array_filter(array_merge(
            $this->getDateTimeData(),
            $this->getGpsData()
        )) ?: null;
    }

    /**
     * Get image date and time information from EXIF data.
     *
     * @return array
     */
    protected function getDateTimeData()
    {
        if (! array_key_exists('DateTimeOriginal', $this->exif)) {
            return [];
        }

        $carbon = Carbon::parse($this->exif['DateTimeOriginal']);

        return [
            'year' => $carbon->year,
            'month' => $carbon->month,
            'day' => $carbon->day,
            'time' => $carbon->format('H:i'),
        ];
    }

    /**
     * Get image GPS information from EXIF data.
     *
     * @return array
     */
    protected function getGpsData()
    {
        if (! $this->hasGpsData()) {
            return [];
        }

        return array_filter([
            'latitude' => $this->extractLatitude(),
            'longitude' => $this->extractLongitude(),
            'elevation' => $this->extractElevation(),
            'accuracy' => $this->extractGpsPrecision(),
        ]);
    }

    /**
     * Check if we have required GPS information.
     *
     * @return bool
     */
    protected function hasGpsData()
    {
        $requiredParams = ['GPSLatitude', 'GPSLongitude', 'GPSLatitudeRef', 'GPSLongitudeRef'];

        foreach ($requiredParams as $param) {
            if (! array_key_exists($param, $this->exif)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Extract latitude from EXIF.
     *
     * @return float
     */
    protected function extractLatitude()
    {
        $coefficient = 1;

        if ($this->exif['GPSLatitudeRef'] == 'S') {
            $coefficient = -1;
        }

        return $this->convertCoordinateToDecimal($this->exif['GPSLatitude'], $coefficient);
    }

    /**
     * Extract longitude from EXIF.
     *
     * @return float
     */
    protected function extractLongitude()
    {
        $coefficient = 1;

        if ($this->exif['GPSLongitudeRef'] == 'W') {
            $coefficient = -1;
        }

        return $this->convertCoordinateToDecimal($this->exif['GPSLongitude'], $coefficient);
    }

    /**
     * Extract elevation from EXIF.
     *
     * @return int|null
     */
    protected function extractElevation()
    {
        if (! array_key_exists('GPSAltitude', $this->exif)) {
            return;
        }

        return round($this->calculateNumber($this->exif['GPSAltitude']));
    }

    /**
     * Extract GPS precision from EXIF.
     *
     * @return int|null
     */
    protected function extractGpsPrecision()
    {
        if (! array_key_exists('GPSDOP', $this->exif)) {
            return;
        }

        return round($this->calculateNumber($this->exif['GPSDOP']));
    }

    /**
     * Convert coordinate from EXIF into decimal number.
     *
     * @param  array $coordinateData
     * @param  int $coefficient
     * @return float
     */
    protected function convertCoordinateToDecimal($coordinateData, $coefficient)
    {
        list($degrees, $minutes, $seconds) = array_map(
            [$this, 'calculateNumber'],
            $coordinateData
        );

        return $coefficient * ($degrees + ($minutes / 60) + ($seconds / 3600));
    }

    /**
     * Convert part of coordinate into number, making required division.
     *
     * @param  string $value
     * @return int
     */
    protected function calculateNumber($value)
    {
        $temp = explode('/', $value);

        if (isset($temp[1]) && '0' === $temp[1]) {
            return;
        }

        return $temp[0] / $temp[1] ?? 1;
    }
}
