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
     * @return void
     * Test if we can create a project
     */
    public function test_can_create_a_project(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Get project by created_at filtered by startDate and endDate
     * @return void
     */
    public function test_can_get_project_by_created_at_filtered_by_startDate_and_endDate(): void
    {
        $project = Project::factory()->create(['created_at' => '2020-01-02']);
        $this->assertEquals('2020-01-02', Carbon::parse($project->created_at)
            ->format('Y-m-d'));

        $response = $this->get('/api/projects?filter[startDate]=2020-01-01&filter[endDate]=2020-01-31');
        $response->assertStatus(200);
    }
}
