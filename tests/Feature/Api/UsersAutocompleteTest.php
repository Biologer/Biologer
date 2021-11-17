<?php

namespace Tests\Feature\Api;

use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UsersAutocompleteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');
    }

    /** @test */
    public function can_fetch_basic_users_list_for_autocomplete()
    {
        $user = User::factory()->create([
            'first_name' => 'X',
            'last_name' => 'Y',
        ])->assignRoles('admin');
        Passport::actingAs($user);

        $userJane = User::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);

        $userJohn = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
        ]);

        $response = $this->get('/api/autocomplete/users');

        $response->assertSuccessful();
        $this->customAssertArraySubset([
            [
                'id' => $userJane->id,
                'full_name' => 'Jane Doe',
                'email' => 'jane@example.com',
            ],
            [
                'id' => $userJohn->id,
                'full_name' => 'John Doe',
                'email' => 'john@example.com',
            ],
        ], $response->json('data'));
    }

    /** @test */
    public function user_list_is_paginated()
    {
        $user = User::factory()->create();
        $user->assignRoles('admin');
        Passport::actingAs($user);

        User::factory(20)->create();

        $response = $this->get('/api/autocomplete/users');

        $response->assertSuccessful();
        $this->assertCount(10, $response->json('data'));
    }

    /** @test */
    public function filtering_users_by_name()
    {
        $user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);
        $user->assignRoles('admin');
        Passport::actingAs($user);

        $userJane = User::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);

        $userJohn = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
        ]);

        $response = $this->get('/api/autocomplete/users?'.http_build_query([
            'name' => 'Jane John',
        ]));

        $response->assertSuccessful();
        $this->assertCount(2, $response->json('data'));
    }
}
