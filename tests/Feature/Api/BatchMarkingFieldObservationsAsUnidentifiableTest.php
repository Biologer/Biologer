<?php

namespace Tests\Feature\Api;

use App\User;
use App\Taxon;
use App\FieldObservation;
use Tests\TestCase;
use Tests\ObservationFactory;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BatchMarkingFieldObservationsAsUnidentifiableTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');
    }

    /** @test */
    function authenticated_user_that_curates_the_taxa_of_all_the_field_observation_can_mark_them_as_unapprovable()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create()->assignRole('curator');
        Passport::actingAs($user);
        $taxon = factory(Taxon::class)->create();
        $taxon->curators()->attach($user);
        $fieldObservations = ObservationFactory::createManyUnnapprovedFieldObservations(3, [
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        $ids = $fieldObservations->pluck('id')->all();
        $response = $this->postJson('/api/unidentifiable-field-observations/batch', [
            'field_observation_ids' => $ids,
        ]);

        $response->assertSuccessful();

        $fresh = FieldObservation::whereIn('id', $ids);
        $this->assertEquals(3, $fresh->count());
        $fresh->each(function ($fieldObservation) {
            $this->assertTrue($fieldObservation->unidentifiable);
            $this->assertFalse($fieldObservation->isApproved());
        });
    }
}
