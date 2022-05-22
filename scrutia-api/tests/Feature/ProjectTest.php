<?php

namespace Tests\Feature;

use App\Models\Like;
use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Carbon;

class ProjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @return void
     * Test unauthenticated user cannot create a project
     */
    public function test_unauthenticated_user_cannot_create_a_project(): void
    {
        $title = $this->faker->word();
        $project_request = [
            'title' => $title,
            'description' => $this->faker->text(),
            'tags' => [
                'API',
                'Love'
            ]
        ];

        $response = $this->post('/api/projects', $project_request);

        $response->assertRedirect('api/login');
        $this->assertDatabaseMissing('projects', [
            'id' => 1,
            'title' => $title
        ]);
    }

    /**
     * @return void
     * Test user can create a project
     */
    public function test_user_can_create_a_project(): void
    {
        $user = User::factory()->create();
        $title = $this->faker->word();
        $project_request = [
            'title' => $title,
            'description' => $this->faker->text(),
            'tags' => [
                [
                "title" => 'API',
                ],
                [
                "title" => 'Love'
                ]
            ]
        ];

        $response = $this->actingAs($user)->post('/api/projects', $project_request);
        $response->assertCreated();
        $this->assertDatabaseHas('projects', [
            'title' => $title
        ]);
        $this->assertDatabaseCount('tags', 2);
        $this->assertDatabaseCount('project_tag', 2);
    }

    /**
     * @return void
     * Test user with more than 50 reputation can create multiple projects
     */
    public function test_user_with_more_than_50_reputation_can_create_multiple_projects(): void
    {
        $user = User::factory()->create();
        $title = $this->faker->word();
        $project_request = [
            'title' => $title,
            'description' => $this->faker->text(),
            'tags' => [
                [
                    "title" => 'API',
                ],
                [
                    "title" => 'Love'
                ]
            ]
        ];

        $response = $this->actingAs($user)->post('/api/projects', $project_request);
        $response->assertCreated();

        $response = $this->actingAs($user)->post('/api/projects', $project_request);
        $response->assertCreated();

        $response = $this->actingAs($user)->post('/api/projects', $project_request);
        $response->assertCreated();

        $this->assertDatabaseCount('projects', 3);
    }

    /**
     * @return void
     * Test user with less than 50 reputation can create only one project
     */
    public function test_user_with_less_than_50_reputation_can_create_only_one_project(): void
    {
        $user = User::factory()->create([
            "reputation" => 40
        ]);
        $title = $this->faker->word();
        $project_request = [
            'title' => $title,
            'description' => $this->faker->text(),
            'tags' => [
                [
                    "title" => 'API',
                ],
                [
                    "title" => 'Love'
                ]
            ]
        ];

        $response = $this->actingAs($user)->post('/api/projects', $project_request);
        $response->assertCreated();

        $response = $this->actingAs($user)->post('/api/projects', $project_request);
        $response->assertStatus(403);

        $this->assertDatabaseCount('projects', 1);
    }

    /**
     * @return void
     * Test idea with more or equals than 50 upvote can be promoted
     */
    public function test_idea_can_be_promoted_if_there_is_more_than_50_upvote(): void
    {
        $project = Project::factory()->create([
            "status" => Status::IDEE
        ]);

        $version = Version::factory()->create();
        $version->project()->associate($project);
        $version->save();


        Like::factory(50)->create([
            "value" => 1,
            "likeable_type" => $version->getMorphClass(),
            "likeable_id" => $version->id
        ]);

        $response = $this->actingAs($project->user)->put('/api/projects/'.$project->id."/promote");
        $response->assertSuccessful();
        $this->assertDatabaseHas("projects", [
            "id" => $project->id,
            "status" => Status::INITIATIVE
        ]);
    }

    /**
     * @return void
     * Test idea with less than 50 upvote cannot be promoted
     */
    public function test_idea_cannot_be_promoted_if_there_is_less_than_50_upvote(): void
    {
        $project = Project::factory()->create([
            "status" => Status::IDEE
        ]);

        $version = Version::factory()->create();
        $version->project()->associate($project);
        $version->save();


        Like::factory(49)->create([
            "value" => 1,
            "likeable_type" => $version->getMorphClass(),
            "likeable_id" => $version->id
        ]);

        $response = $this->actingAs($project->user)->put('/api/projects/'.$project->id."/promote");
        $response->assertStatus(403);
        $this->assertDatabaseMissing("projects", [
            "id" => $project->id,
            "status" => Status::INITIATIVE
        ]);
    }

    /**
     * @return void
     * Test initiative cannot be promoted
     */
    public function test_initiative_cannot_be_promoted(): void
    {
        $project = Project::factory()->create([
            "status" => Status::INITIATIVE
        ]);

        $response = $this->actingAs($project->user)->put('/api/projects/'.$project->id."/promote");
        $response->assertStatus(400);
        $this->assertDatabaseHas("projects", [
            "id" => $project->id,
            "status" => Status::INITIATIVE
        ]);
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
