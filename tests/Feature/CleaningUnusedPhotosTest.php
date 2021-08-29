<?php

namespace Tests\Feature;

use App\Photo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\ObservationFactory;
use Tests\TestCase;

class CleaningUnusedPhotosTest extends TestCase
{
    /** @test */
    public function photos_that_are_not_attached_to_any_observation_are_removed()
    {
        Storage::fake('public');

        $fieldObservation = ObservationFactory::createFieldObservation();
        $usedPhoto = $fieldObservation->photos()->save(Photo::factory()->make());
        $unusedNewPhoto = Photo::factory()->create();
        $unusedOldPhoto = Photo::factory()->create(['updated_at' => Carbon::yesterday()->subDay()]);

        $this->assertTrue($usedPhoto->exists);
        $this->assertTrue($unusedNewPhoto->exists);
        $this->assertTrue($unusedOldPhoto->exists);

        $this->artisan('photos:clean');

        $this->assertNotNull($usedPhoto->fresh());
        $this->assertNotNull($unusedNewPhoto->fresh());
        $this->assertNull($unusedOldPhoto->fresh());

        Storage::disk('public')->assertExists($usedPhoto->path);
        Storage::disk('public')->assertExists($unusedNewPhoto->path);
        Storage::disk('public')->assertMissing($unusedOldPhoto->path);
    }
}
