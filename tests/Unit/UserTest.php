<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_fillable_attributes()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
    }

    public function test_user_hidden_password()
    {
        $user = User::factory()->create([
            'password' => 'secret',
        ]);

        $this->assertArrayNotHasKey('password', $user->toArray());
    }
}
