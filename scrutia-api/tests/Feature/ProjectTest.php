<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
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

    public function test_can_update_a_project()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $attributes = [
            'title' => 'Changed',
            'description' => 'Changed',
        ];
        $this->patch($project->path(), $attributes)->assertRedirect($project->path());
        $this->assertDatabaseHas('projects', $attributes);
    }

    public function test_can_delete_a_project()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $this->delete($project->path())->assertRedirect('/projects');
        $this->assertDatabaseMissing('projects', $project->only('id'));
    }
}
