<?php

namespace Tests\Feature\Api;

use App\User;
use App\Taxon;
use Tests\TestCase;
use Tests\ObservationFactory;
use Laravel\Passport\Passport;

class GetFieldObservationsTest extends TestCase
{
    /** @test */
    public function authenticated_user_can_get_their_field_observations()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo', 'rank' => 'species']);

        $observation = ObservationFactory::createFieldObservation([
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
        ]);

        $response = $this->get('/api/my/field-observations');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['id' => $observation->id, 'taxon_id' => $taxon->id],
            ],
        ]);
    }

    /** @test */
    public function authenticated_user_can_get_their_field_observations_filtered_by_taxon_name()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $cerambyxCerdo = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo', 'rank' => 'species']);
        $cerambyxScopolii = factory(Taxon::class)->create(['name' => 'Cerambyx scopolii', 'rank' => 'species']);

        $cerambyxCerdoObservation = ObservationFactory::createFieldObservation([
            'taxon_id' => $cerambyxCerdo->id,
            'created_by_id' => $user->id,
        ]);

        ObservationFactory::createFieldObservation([
            'taxon_id' => $cerambyxScopolii->id,
            'created_by_id' => $user->id,
        ]);

        $response = $this->get('/api/my/field-observations?taxon=Cerambyx+cerdo');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['id' => $cerambyxCerdoObservation->id, 'taxon_id' => $cerambyxCerdo->id],
            ],
        ]);
    }

    /** @test */
    public function authenticated_user_can_get_their_field_observations_filtered_by_taxon_id()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $cerambyxCerdo = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo', 'rank' => 'species']);
        $cerambyxScopolii = factory(Taxon::class)->create(['name' => 'Cerambyx scopolii', 'rank' => 'species']);

        $cerambyxCerdoObservation = ObservationFactory::createFieldObservation([
            'taxon_id' => $cerambyxCerdo->id,
            'created_by_id' => $user->id,
        ]);

        ObservationFactory::createFieldObservation([
            'taxon_id' => $cerambyxScopolii->id,
            'created_by_id' => $user->id,
        ]);

        $response = $this->get('/api/my/field-observations?taxon=Cerambyx&taxonId='.$cerambyxCerdo->id);

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['id' => $cerambyxCerdoObservation->id, 'taxon_id' => $cerambyxCerdo->id],
            ],
        ]);
    }

    /** @test */
    public function authenticated_user_can_get_their_field_observations_filtered_by_taxon_id_including_descendants()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $cerambyx = factory(Taxon::class)->create([
            'name' => 'Cerambyx',
            'rank' => 'genus',
        ]);
        $cerambyxCerdo = factory(Taxon::class)->create([
            'name' => 'Cerambyx cerdo',
            'rank' => 'species',
            'parent_id' => $cerambyx->id,
        ]);
        $cerambyxScopolii = factory(Taxon::class)->create([
            'name' => 'Cerambyx scopolii',
            'rank' => 'species',
            'parent_id' => $cerambyx->id,
        ]);

        $cerambyxCerdoObservation = ObservationFactory::createFieldObservation([
            'taxon_id' => $cerambyxCerdo->id,
            'created_by_id' => $user->id,
        ]);

        $cerambyxScopoliiObservation = ObservationFactory::createFieldObservation([
            'taxon_id' => $cerambyxScopolii->id,
            'created_by_id' => $user->id,
        ]);

        $response = $this->get('/api/my/field-observations?taxon=Cerambyx&includeChildTaxa=1&taxonId='.$cerambyx->id);

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['id' => $cerambyxCerdoObservation->id, 'taxon_id' => $cerambyxCerdo->id],
                ['id' => $cerambyxScopoliiObservation->id, 'taxon_id' => $cerambyxScopolii->id],
            ],
        ]);
    }
}
