<?php

namespace Tests\Feature\Api;

use App\Models\Photo;
use App\Models\Taxon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\ObservationFactory;
use Tests\TestCase;

class GetTaxonPublicPhotosTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_get_public_photos_for_taxon()
    {
        Storage::fake(config('biologer.photos_disk'));

        $taxon = Taxon::factory()->create();

        $photos = ObservationFactory::createManyFieldObservations(3, [
            'taxon_id' => $taxon->id,
        ])->map(function ($fieldObservation) {
            $photo = Photo::factory()->public()->create();

            $fieldObservation->observation->photos()->attach($photo);

            return $photo;
        });

        $response = $this->getJson("/api/taxa/{$taxon->id}/public-photos");

        $response->assertOk();
        $this->assertCount(3, $response->json('data'));
        $publicUrls = $photos->pluck('public_url');
        foreach ($response->json('data.*.url') as $url) {
            $publicUrls->assertContains(function ($publicUrl) use ($url) {
                return Str::contains($url, $publicUrl);
            });
        }
    }
}
