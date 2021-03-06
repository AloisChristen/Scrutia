<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $response->assertSuccessful();
        $this->assertNotEmpty($response->getContent());
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
        ]);
    }
    public function test_username_not_found()
    {
        $response = $this->post('/api/login', [
            'username' => 'qwertz',
            'password' => 'qwertz',
        ]);

        $this->assertTrue($response->status() == 404);
        $this->assertNotEmpty($response->getContent());
    }
    
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'username' => $user->username,
            'password' => 'sds',
        ]);

        $this->assertNotEmpty($response->getContent());
        $this->assertTrue($response->status() == 401);
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
        ]);
    }


}
