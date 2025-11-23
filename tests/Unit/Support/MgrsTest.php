<?php

namespace Tests\Unit\Support;

use PHPUnit\Framework\Attributes\Test;
use App\Support\Mgrs;
use PHPUnit\Framework\TestCase;

final class MgrsTest extends TestCase
{
    #[Test]
    public function it_can_calculate_mgrs_10k_square_identifier(): void
    {
        $this->assertSame('34TDR01', Mgrs::makeFromLatLong(45.247177, 19.813558)->to10k());
    }

    #[Test]
    public function it_can_calculate_point_with_coordinates_of_mgrs_10k_square_center(): void
    {
        $mgrs = Mgrs::makeFromLatLong(45.247177, 19.813558);
        $center = $mgrs->centerOf10kLatLng();
        $centerMgrs = Mgrs::makeFromLatLong(
            $center->getLatitude()->getValue(),
            $center->getLongitude()->getValue()
        );

        $this->assertSame($centerMgrs->to10k(), $mgrs->to10k());
        $this->assertEqualsWithDelta(45.28208, $center->getLatitude()->getValue(), 0.00001);
        $this->assertEqualsWithDelta(19.78869, $center->getLongitude()->getValue(), 0.00001);
    }
}
