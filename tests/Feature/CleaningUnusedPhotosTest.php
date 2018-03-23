<?php

namespace Tests\Feature;

use App\Photo;

use Tests\TestCase;
use Tests\ObservationFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CleaningUnusedPhotosTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function photos_that_are_not_attached_to_any_observation_are_removed()
    {
        Storage::fake('public');

        $fieldObservation = ObservationFactory::createFieldObservation();
        $usedPhoto = $fieldObservation->photos()->save(factory(Photo::class)->make());
        $unusedPhoto = factory(Photo::class)->create();

        $this->assertTrue($usedPhoto->exists);
        $this->assertTrue($unusedPhoto->exists);

        $this->artisan('photos:clean');

        $this->assertNotNull($usedPhoto->fresh());
        $this->assertNull($unusedPhoto->fresh());

        Storage::disk('public')->assertExists($usedPhoto->path);
        Storage::disk('public')->assertMissing($unusedPhoto->path);
    }
}
