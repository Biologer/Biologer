<?php

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\Test;
use App\Taxon;
use App\User;
use Laravel\Passport\Passport;
use Tests\ObservationFactory;
use Tests\TestCase;

class GetFieldObservationsTest extends TestCase
{
    #[Test]
    public function authenticated_user_can_get_their_field_observations()
    {
        Passport::actingAs($user = User::factory()->create());

        $taxon = Taxon::factory()->create(['name' => 'Cerambyx cerdo', 'rank' => 'species']);

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

    #[Test]
    public function authenticated_user_can_get_their_field_observations_filtered_by_taxon_name()
    {
        Passport::actingAs($user = User::factory()->create());

        $cerambyxCerdo = Taxon::factory()->create(['name' => 'Cerambyx cerdo', 'rank' => 'species']);
        $cerambyxScopolii = Taxon::factory()->create(['name' => 'Cerambyx scopolii', 'rank' => 'species']);

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

    #[Test]
    public function authenticated_user_can_get_their_field_observations_filtered_by_taxon_id()
    {
        Passport::actingAs($user = User::factory()->create());

        $cerambyxCerdo = Taxon::factory()->create(['name' => 'Cerambyx cerdo', 'rank' => 'species']);
        $cerambyxScopolii = Taxon::factory()->create(['name' => 'Cerambyx scopolii', 'rank' => 'species']);

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

    #[Test]
    public function authenticated_user_can_get_their_field_observations_filtered_by_taxon_id_including_descendants()
    {
        Passport::actingAs($user = User::factory()->create());

        $cerambyx = Taxon::factory()->create([
            'name' => 'Cerambyx',
            'rank' => 'genus',
        ]);
        $cerambyxCerdo = Taxon::factory()->create([
            'name' => 'Cerambyx cerdo',
            'rank' => 'species',
            'parent_id' => $cerambyx->id,
        ]);
        $cerambyxScopolii = Taxon::factory()->create([
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

    #[Test]
    public function authenticated_user_can_get_their_field_observations_sorted_by_taxon_name()
    {
        Passport::actingAs($user = User::factory()->create());

        $cerambyx = Taxon::factory()->create([
            'name' => 'Cerambyx',
            'rank' => 'genus',
        ]);
        $cerambyxCerdo = Taxon::factory()->create([
            'name' => 'Cerambyx cerdo',
            'rank' => 'species',
            'parent_id' => $cerambyx->id,
        ]);
        $cerambyxScopolii = Taxon::factory()->create([
            'name' => 'Cerambyx scopolii',
            'rank' => 'species',
            'parent_id' => $cerambyx->id,
        ]);

        $abiesAlba = Taxon::factory()->create([
            'name' => 'Abies alba',
            'rank' => 'species',
        ]);

        $cerambyxCerdoObservation = ObservationFactory::createFieldObservation([
            'taxon_id' => $cerambyxCerdo->id,
            'created_by_id' => $user->id,
        ]);

        $cerambyxScopoliiObservation = ObservationFactory::createFieldObservation([
            'taxon_id' => $cerambyxScopolii->id,
            'created_by_id' => $user->id,
        ]);

        $abiesAlbaObservation = ObservationFactory::createFieldObservation([
            'taxon_id' => $abiesAlba->id,
            'created_by_id' => $user->id,
        ]);

        $response = $this->get('/api/my/field-observations?sort_by=taxon_name.asc');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                ['id' => $abiesAlbaObservation->id, 'taxon_id' => $abiesAlba->id],
                ['id' => $cerambyxCerdoObservation->id, 'taxon_id' => $cerambyxCerdo->id],
                ['id' => $cerambyxScopoliiObservation->id, 'taxon_id' => $cerambyxScopolii->id],
            ],
        ]);
    }
}
