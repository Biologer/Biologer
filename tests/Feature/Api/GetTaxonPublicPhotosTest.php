<?php

namespace Tests\Feature\Api;

use App\Photo;
use App\Taxon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\ObservationFactory;
use Tests\TestCase;

class GetTaxonPublicPhotosTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_get_public_photos_for_taxon()
    {
        $taxon = factory(Taxon::class)->create();

        $photos = ObservationFactory::createManyFieldObservations(3, [
            'taxon_id' => $taxon->id,
        ])->map(function ($fieldObservation) {
            $photo = factory(Photo::class)->state('public')->create();

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
