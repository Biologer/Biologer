<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function full_name_is_concatenation_of_first_and_last_names()
    {
        $user = factory(User::class)->make([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertSame('John Doe', $user->full_name);
    }

    /** @test */
    public function verification_token_is_created_for_new_user()
    {
        $user = factory(User::class)->states('unverified')->create();

        $this->assertNotNull($user->verificationToken);
        $this->assertNotEmpty($user->verificationToken->token);
    }
}
