<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use App\Observation;
use App\Photo;
use Illuminate\Support\Facades\Storage;
use Tests\ObservationFactory;
use Tests\TestCase;

final class ObservationTest extends TestCase
{
    #[Test]
    public function can_check_if_full_date_is_present(): void
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

    #[Test]
    public function can_be_unnaproved(): void
    {
        $observation = ObservationFactory::createFieldObservation()->observation;

        $this->assertTrue($observation->isApproved());

        $observation->unapprove();

        $this->assertFalse($observation->isApproved());
    }

    #[Test]
    public function updating_observer_updates_author_of_related_photos(): void
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
