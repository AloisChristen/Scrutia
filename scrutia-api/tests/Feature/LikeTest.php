<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LikeTest extends TestCase
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

    public function test_guest_cannot_like_anything()
    {
        $response = $this->post('/replies/1/likes');

        $response->assertRedirect('/login');
    }

    public function test_an_authenticated_user_can_like_any_reply()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $reply = create('App\Reply');

        $response = $this->post('/api/replies/' . $reply->id . '/likes');

        $response->assertStatus(200);
    }

    public function test_an_authenticated_user_can_unlike_any_reply()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $reply = create('App\Reply');

        $response = $this->delete('/api/replies/' . $reply->id . '/likes');
        $response->assertStatus(200);


    }

}
