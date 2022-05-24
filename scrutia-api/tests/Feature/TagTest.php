<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
{
    /**
     * Test unauthenticated users can list tags
     *
     * @return void
     */
    public function test_unauthenticated_users_can_list_tags(): void
    {
        $response = $this->get('/api/tags');
        $response->assertStatus(200);
    }
}
