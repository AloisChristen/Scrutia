<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Carbon;

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

    /**
     * Get project by created_at filtered by startDate and endDate
     * @return void
     */
    public function test_can_get_project_by_created_at_filtered_by_startDate_and_endDate(): void
    {
        $project = Project::factory()->create(['created_at' => '2020-01-02']);
        $this->assertEquals('2020-01-01', Carbon::parse($project->created_at)
            ->format('Y-m-d'));

        $response = $this->get('/api/projects?filter[startDate]=2020-01-01&filter[endDate]=2020-01-31');
        $response->assertStatus(200);
    }
}
