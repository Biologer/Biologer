<?php

namespace Tests\Unit\DEM;

use App\DEM\RunalyzeReader;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Runalyze\DEM\Interpolation\InterpolationInterface;
use Runalyze\DEM\Provider\ProviderInterface;

class RunalyzeReaderTest extends TestCase
{
    #[Test]
    public function it_uses_provider_to_get_elevation_for_latitude_and_longitude()
    {
        $expectedElevation = 200;

        $reader = new RunalyzeReader($this->fakeProvider($expectedElevation));

        $this->assertEquals($expectedElevation, $reader->getElevation(21.121212, 43.434343));
    }

    #[Test]
    public function it_returns_null_if_no_provider_can_get_elevation_for_give_location()
    {
        $reader = new RunalyzeReader($this->fakeProvider(200, false));

        $this->assertNull($reader->getElevation(21.121212, 43.434343));
    }

    private function fakeProvider($elevation, $hasDataFor = true)
    {
        return new class($elevation, $hasDataFor) implements ProviderInterface {
            private $elevation;
            private $hasDataFor;

            public function __construct($elevation, $hasDataFor)
            {
                $this->elevation = $elevation;
                $this->hasDataFor = $hasDataFor;
            }

            public function setInterpolation(InterpolationInterface $interpolation)
            {
                //
            }

            public function hasDataFor(array $latitudeLongitudes)
            {
                return $this->hasDataFor;
            }

            public function getElevations(array $latitudes, array $longitudes)
            {
                //
            }

            public function getElevation($latitude, $longitude)
            {
                return $this->elevation;
            }
        };
    }
}
