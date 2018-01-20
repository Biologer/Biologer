<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Observation;

class ObservationTest extends TestCase
{
    /** @test */
    public function can_check_if_full_date_is_present()
    {
        list(
            $observation1, $observation2, $observation3, $observation4
        ) = factory(Observation::class, 4)->make([
            'year' => '2017',
            'month' => '07',
            'day' => '15',
            'created_by_id' => null,
        ]);

        $observation2->month = null;
        $observation3->day = null;
        $observation4->month = null;
        $observation4->day = null;

        $this->assertTrue($observation1->isDateComplete());
        $this->assertFalse($observation2->isDateComplete());
        $this->assertFalse($observation3->isDateComplete());
        $this->assertFalse($observation4->isDateComplete());
    }
}
