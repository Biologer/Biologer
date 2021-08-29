<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function full_name_is_concatenation_of_first_and_last_names()
    {
        $user = User::factory()->make([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertSame('John Doe', $user->full_name);
    }
}
