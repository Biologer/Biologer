<?php

namespace Tests\Feature;

use App\Photo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\ObservationFactory;
use Tests\TestCase;

class CleaningUnusedPhotosTest extends TestCase
{
    #[Test]
    public function photos_that_are_not_attached_to_any_observation_are_removed()
    {
        $photosDisk = config('biologer.photos_disk');
        Storage::fake($photosDisk);

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

        Storage::disk($photosDisk)->assertExists($usedPhoto->path);
        Storage::disk($photosDisk)->assertExists($unusedNewPhoto->path);
        Storage::disk($photosDisk)->assertMissing($unusedOldPhoto->path);
    }
}
