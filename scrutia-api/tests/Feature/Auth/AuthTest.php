<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use function Tests\Feature\factory;

class AuthTest extends TestCase
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

    public function test_login()
    {

        $user = factory(User::class)->create();

        $response = $this->post('/api/login', [
            'username' => $user->username,
            'password' => $user->password,
        ]);

        $response->assertStatus(200);
    }

    public function test_login_fail()
    {

        $user = factory(User::class)->create();

        $response = $this->post('/api/login', [
            'username' => $user->username,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401);
    }

    public function test_logout()
    {

        $user = factory(User::class)->create();

        $response = $this->post('/api/login', [
            'username' => $user->username,
            'password' => $user->password,
        ]);

        $response->assertStatus(200);

        $response = $this->post('/api/logout');

        $response->assertStatus(200);
    }

}
