<?php

namespace Tests\Unit\Support;

use App\Support\Exif;
use PHPUnit\Framework\TestCase;
use Tests\CustomAssertArraySubset;

class ExifTest extends TestCase
{
    use CustomAssertArraySubset;

    /** @test */
    public function can_extract_date_time_data()
    {
        $exif = new Exif([
            'DateTimeOriginal' => '2018:01:01 10:00:00',
        ]);

        $this->customAssertArraySubset([
            'year' => 2018,
            'month' => 1,
            'day' => 1,
            'time' => '10:00',
        ], $exif->format());
    }

    /** @test */
    public function can_extract_gps_data()
    {
        $exif = new Exif([
            'GPSLatitude' => ['46/1', '5403/100', '0/1'],
            'GPSLongitude' => ['7/1', '880/100', '0/1'],
            'GPSLatitudeRef' => 'N',
            'GPSLongitudeRef' => 'E',
            'GPSAltitude' => '634/1',
            'GPSDOP' => '3/1',
        ]);

        $this->assertEquals([
            'latitude' => 46.9005,
            'longitude' => 7.1466666666666665,
            'elevation' => 634,
            'accuracy' => 3,
        ], $exif->format());
    }

    /** @test */
    public function handles_devision_by_zero_in_decimal_number_definitions()
    {
        $exif = new Exif([
            'GPSLatitude' => ['46/1', '5403/0', '0/1'], // Minutes devided
            'GPSLongitude' => ['7/1', '880/100', '0/1'],
            'GPSLatitudeRef' => 'N',
            'GPSLongitudeRef' => 'E',
            'GPSAltitude' => '634/1',
            'GPSDOP' => '3/1',
        ]);

        $this->assertEquals([
            'latitude' => 46,
            'longitude' => 7.1466666666666665,
            'elevation' => 634,
            'accuracy' => 3,
        ], $exif->format());
    }
}
