<?php

namespace Tests\Feature;

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
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];
        $this->post('/projects', $attributes)->assertRedirect('/projects');
        $this->assertDatabaseHas('projects', $attributes);
        $this->get('/projects')->assertSee($attributes['title']);
    }

    public function test_can_update_a_project()
    {
        $this->withoutExceptionHandling();
        $project = factory('App\Project')->create();
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
        $project = factory('App\Project')->create();
        $this->delete($project->path())->assertRedirect('/projects');
        $this->assertDatabaseMissing('projects', $project->only('id'));
    }



}
