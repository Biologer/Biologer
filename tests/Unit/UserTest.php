<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use App\User;
use Tests\TestCase;

final class UserTest extends TestCase
{
    #[Test]
    public function full_name_is_concatenation_of_first_and_last_names(): void
    {
        $user = User::factory()->make([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertSame('John Doe', $user->full_name);
    }
}
