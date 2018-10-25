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
}
