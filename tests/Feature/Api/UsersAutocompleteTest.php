<?php

namespace Tests\Feature\Api;

use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;

use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersAutocompleteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');
    }

    /** @test */
    public function can_fetch_basic_users_list_for_autocomplete()
    {
        $user = factory(User::class)->create([
            'first_name' => 'X',
            'last_name' => 'Y',
        ])->assignRoles('admin');
        Passport::actingAs($user);

        $userJane = factory(User::class)->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);

        $userJohn = factory(User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
        ]);

        $response = $this->get('/api/autocomplete/users');

        $response->assertSuccessful();
        $this->assertArraySubset([
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
        $user = factory(User::class)->create();
        $user->assignRoles('admin');
        Passport::actingAs($user);

        factory(User::class, 20)->create();

        $response = $this->get('/api/autocomplete/users');

        $response->assertSuccessful();
        $this->assertCount(10, $response->json('data'));
    }

    /** @test */
    public function filtering_users_by_name()
    {
        $user = factory(User::class)->create([
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);
        $user->assignRoles('admin');
        Passport::actingAs($user);

        $userJane = factory(User::class)->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);

        $userJohn = factory(User::class)->create([
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
