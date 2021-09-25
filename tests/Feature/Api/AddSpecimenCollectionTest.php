<?php

namespace Tests\Feature\Api;

use App\SpecimenCollection;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AddSpecimenCollectionTest extends TestCase
{
    protected function validParams()
    {
        return [
            'name' => 'Entomological Collection of Natural History Museum',
            'code' => 'ECNHM',
            'institution_name' => 'Natural History Museum',
            'institution_code' => 'NHMBG',
        ];
    }

    /**
     * @test
     */
    public function guest_cannot_add_specimen_collection()
    {
        $count = SpecimenCollection::count();

        $response = $this->postJson('/api/specimen-collections', $this->validParams());

        $response->assertUnauthorized();
        SpecimenCollection::assertCount($count);
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_add_specimen_collections()
    {
        $count = SpecimenCollection::count();
        Passport::actingAs(User::factory()->create());

        $response = $this->postJson('/api/specimen-collections', $this->validParams());

        $response->assertForbidden();
        SpecimenCollection::assertCount($count);
    }

    /**
     * @test
     */
    public function authorized_user_can_add_new_specimen_collection()
    {
        $this->seed('RolesTableSeeder');
        Passport::actingAs(User::factory()->create()->assignRoles('curator'));

        $response = $this->withoutExceptionHandling()->postJson('/api/specimen-collections', [
            'name' => 'Entomological Collection of Natural History Museum',
            'code' => 'ECNHM',
            'institution_name' => 'Natural History Museum',
            'institution_code' => 'NHMBG',
        ]);

        $response->assertCreated();

        $publication = SpecimenCollection::find($response->json('data.id'));
        $this->assertEquals('Entomological Collection of Natural History Museum', $publication->name);
        $this->assertEquals('ECNHM', $publication->code);
        $this->assertEquals('Natural History Museum', $publication->institution_name);
        $this->assertEquals('NHMBG', $publication->institution_code);
    }
}
