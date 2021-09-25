<?php

namespace Tests\Unit;

use App\Observation;
use App\Photo;
use Illuminate\Support\Facades\Storage;
use Tests\ObservationFactory;
use Tests\TestCase;

class ObservationTest extends TestCase
{
    /** @test */
    public function can_check_if_full_date_is_present()
    {
        list(
            $observation1, $observation2, $observation3, $observation4
        ) = Observation::factory(4)->make([
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

    /** @test */
    public function can_be_unnaproved()
    {
        $observation = ObservationFactory::createFieldObservation()->observation;

        $this->assertTrue($observation->isApproved());

        $observation->unapprove();

        $this->assertFalse($observation->isApproved());
    }

    /** @test */
    public function updating_observer_updates_author_of_related_photos()
    {
        Storage::fake('public');
        $observation = ObservationFactory::createFieldObservation()->observation;
        $photo = $observation->photos()->save(Photo::factory()->make([
            'path' => 'fake-path/image.jpg',
            'author' => 'test author',
        ]));

        $observation->update(['observer' => 'New observer']);

        $this->assertEquals('New observer', $photo->fresh()->author);
    }
}
