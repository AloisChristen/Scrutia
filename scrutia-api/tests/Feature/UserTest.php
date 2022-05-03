<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_register()
    {
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'test@email.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_login()
    {
        $response = $this->post('/api/login', [
            'email' => 'test@email.com',
            'password' => 'secret'
        ]);

        $this->assertTrue($response);
    }

    public function test_user_can_update()
    {
        $response = $this->put('/api/user/1', [
            'name' => 'John Doe_old',
            'email' => 'testnewEmail@email.com',
            'password' => 'secret2',
            'password_confirmation' => 'secret2'
        ]);

        $this->assertTrue($response);
    }
}
