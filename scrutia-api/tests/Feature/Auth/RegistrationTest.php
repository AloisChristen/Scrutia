<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/register', [
            'username' => 'test',
            'firstname' => 'Alex',
            'lastname' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSuccessful();
        $this->assertNotEmpty($response->getContent());
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'name' => 'Symfony',
        ]);
    }
}
