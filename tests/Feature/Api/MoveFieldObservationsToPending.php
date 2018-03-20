<?php

namespace Tests\Feature\Api;

use App\User;
use App\Taxon;
use Tests\TestCase;
use App\FieldObservation;
use Tests\ObservationFactory;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MoveFieldObservationsToPending extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');
    }

    /** @test */
    public function guest_cannot_mark_field_observation_as_unidentifiable()
    {
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => factory(Taxon::class),
        ]);

        $response = $this->postJson('/api/unidentifiable-field-observations/batch', [
            'field_observation_ids' => [$fieldObservation->id],
        ]);

        $response->assertUnauthorized();
        $this->assertFalse($fieldObservation->fresh()->isApproved());
    }

    /** @test */
    public function authenticated_user_that_curates_the_taxa_of_all_the_field_observation_can_mark_them_as_unapprovable()
    {
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
            'reason' => 'Testing',
        ]);

        $response->assertSuccessful();

        $fresh = FieldObservation::whereIn('id', $ids)->get();
        $fresh->assertCount(3);
        $fresh->each(function ($fieldObservation) {
            $this->assertTrue($fieldObservation->unidentifiable);
            $this->assertFalse($fieldObservation->isApproved());
        });
    }
}
