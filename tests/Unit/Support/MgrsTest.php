<?php

namespace Tests\Unit\Support;

use App\Support\Mgrs;
use PHPUnit\Framework\TestCase;

class MgrsTest extends TestCase
{
    /** @test */
    public function it_can_calculate_mgrs_10k_square_identifier()
    {
        $this->assertSame('34TDR01', Mgrs::makeFromLatLong(45.247177, 19.813558)->to10k());
    }

    /** @test */
    public function it_can_calculate_point_with_coordinates_of_mgrs_10k_square_center()
    {
        $mgrs = Mgrs::makeFromLatLong(45.247177, 19.813558);
        $center = $mgrs->centerOf10kLatLng();
        $centerMgrs = Mgrs::makeFromLatLong($center->getLat(), $center->getLng());

        $this->assertSame($centerMgrs->to10k(), $mgrs->to10k());
        $this->assertEquals(45.28208, $center->getLat());
        $this->assertEquals(19.78869, $center->getLng());
    }
}
