<?php

namespace Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', $user->toArray());

        $token = $user->createToken('test')->plainTextToken;
        $response = $this->actingAs($user)->post('/api/logout', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseMissing('personal_access_tokens',[
            'name' => 'test'
        ]);
    }
}
