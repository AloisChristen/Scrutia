<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase, WithFaker;

    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @return void
     * Test if we can create a project
     */
    public function test_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        $attributes = [
            'title' => $this->faker->text(),
            'description' => $this->faker->text(),
            'author' => $this->faker->name(),
        ];
        $user = User::factory()->create();
        $this->actingAs($user)->post('/api/projects', $attributes);
        $project = Project::first();
        $this->assertEquals($attributes['title'], $project->title);
    }

}
