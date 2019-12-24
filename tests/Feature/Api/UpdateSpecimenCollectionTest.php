<?php

namespace Tests\Feature\Api;

use App\SpecimenCollection;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UpdateSpecimenCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function guest_cannot_update_specimen_collection()
    {
        $specimenCollection = factory(SpecimenCollection::class)->create();

        $response = $this->putJson("/api/specimen-collections/{$specimenCollection->id}", $this->validParams());

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_update_specimen_collection()
    {
        $specimenCollection = factory(SpecimenCollection::class)->create();
        Passport::actingAs(factory(User::class)->create());

        $response = $this->putJson("/api/specimen-collections/{$specimenCollection->id}", $this->validParams());

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_update_specimen_collection()
    {
        $specimenCollection = factory(SpecimenCollection::class)->create();
        $this->seed('RolesTableSeeder');
        Passport::actingAs(factory(User::class)->create()->assignRoles('curator'));

        $response = $this->putJson("/api/specimen-collections/{$specimenCollection->id}", $this->validParams());

        $response->assertSuccessful();
        $specimenCollection->refresh();
        $this->assertEquals('Entomological Collection of Natural History Museum', $specimenCollection->name);
        $this->assertEquals('ECNHM', $specimenCollection->code);
        $this->assertEquals('Natural History Museum', $specimenCollection->institution_name);
        $this->assertEquals('NHMBG', $specimenCollection->institution_code);
    }

    protected function validParams()
    {
        return [
            'name' => 'Entomological Collection of Natural History Museum',
            'code' => 'ECNHM',
            'institution_name' => 'Natural History Museum',
            'institution_code' => 'NHMBG',
        ];
    }
}
