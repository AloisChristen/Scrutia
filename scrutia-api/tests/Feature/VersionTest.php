<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VersionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test that unauthenticated user cannot add a version to a project.
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_add_a_version_to_a_project(): void
    {
        $project = Project::factory()->create();
        $version_request = [
            'project_id' => $project->id,
            'description' => $this->faker->text(),
        ];

        $response =
            $this->post('/api/versions', $version_request);

        $response->assertRedirect('api/login');
        $this->assertDatabaseMissing('versions', $version_request);
    }

    /**
     * Test that only project owner can add a version to his project only if his project is promoted.
     *
     * @return void
     */
    public function test_project_owner_can_add_a_version_to_his_project_if_promoted(): void
    {
        $project = Project::factory()->create([
            "status" => Status::INITIATIVE
        ]);

        $version_request = [
            'project_id' => $project->id,
            'description' => $this->faker->text(),
        ];

        $response =
            $this->actingAs($project->user)
                ->post('/api/versions', $version_request);
        $response->assertCreated();
        $this->assertDatabaseHas('versions', $version_request);
    }

    /**
     * Test that project owner cannot add a version to his project if his project is not promoted.
     *
     * @return void
     */
    public function test_project_owner_cannot_add_a_version_to_his_project_if_not_promoted(): void
    {
        $project = Project::factory()->create();

        $version_request = [
            'project_id' => $project->id,
            'description' => $this->faker->text(),
        ];

        $response =
            $this->actingAs($project->user)
                ->post('/api/versions', $version_request);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('versions', $version_request);
    }

    /**
     * Test that random users cannot add a version to a project.
     *
     * @return void
     */
    public function test_random_user_cannot_add_a_version_to_a_project(): void
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $version_request = [
            'project_id' => $project->id,
            'description' => $this->faker->text(),
        ];

        $response =
            $this->actingAs($user)
                ->post('/api/versions', $version_request);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('versions', $version_request);
    }

    /**
     * Test version number is incrementing for the same project
     *
     * @return void
     */
    public function test_version_number_is_incrementing_after_creating_in_the_same_project(): void
    {
        $project = Project::factory()->create([
            "status" => Status::INITIATIVE
        ]);
        $version_request = [
            'project_id' => $project->id,
            'description' => $this->faker->text(),
        ];

        $this->actingAs($project->user)
                ->post('/api/versions', $version_request);

        $this->assertDatabaseHas('versions', [
            'project_id' => $project->id,
            'number' => 1,
        ]);

        $response = $this->actingAs($project->user)
                ->post('/api/versions', $version_request);

        $response->assertCreated();
        $this->assertDatabaseHas('versions', [
            'project_id' => $project->id,
            'number' => 2,
        ]);
    }

    /**
     * Test that unauthenticated user cannot update a version.
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_update_a_version(): void
    {
        $version = Version::factory()->create();

        $description = $this->faker->text();
        $response =
            $this->put('/api/versions/'.$version->id, [
                'description' => $description,
            ]);

        $response->assertRedirect('api/login');
        $this->assertDatabaseMissing('versions', [
            'id' => $version->id,
            'description' => $description,
        ]);
    }

    /**
     * Test that only project owner can update a version of his project.
     *
     * @return void
     */
    public function test_project_owner_can_update_a_version_of_his_project(): void
    {
        $version = Version::factory()->create();
        $description = $this->faker->text();

        $response =
            $this->actingAs($version->user)
                ->put('/api/versions/'.$version->id, [
                    'description' => $description,
                ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas('versions', [
            'id' => $version->id,
            'description' => $description,
        ]);
    }

    /**
     * Test user with less than 500 reputation cannot update a version
     *
     * @return void
     */
    public function test_user_with_less_than_500_reputation_cannot_update_a_version(): void
    {
        $version = Version::factory()->create();
        $user = User::factory()->create([
            "reputation" => 499
        ]);
        $description = $this->faker->text();

        $response =
            $this->actingAs($user)
                ->put('/api/versions/'.$version->id, [
                    'description' => $description,
                ]);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('versions', [
            'id' => $version->id,
            'description' => $description,
        ]);
    }

    /**
     * Test user with more than 500 reputation can update a version
     *
     * @return void
     */
    public function test_user_with_more_than_500_reputation_can_update_a_version(): void
    {
        $version = Version::factory()->create();
        $user = User::factory()->create([
            "reputation" => 500
        ]);
        $description = $this->faker->text();

        $response =
            $this->actingAs($user)
                ->put('/api/versions/'.$version->id, [
                    'description' => $description,
                ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas('versions', [
            'id' => $version->id,
            'description' => $description,
        ]);
    }

    /**
     * Test that unauthenticated user cannot update a version.
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_delete_a_version(): void
    {
        $version = Version::factory()->create();

        $response =
            $this->put('/api/versions/'.$version->id);

        $response->assertRedirect('api/login');
        $this->assertDatabaseHas('versions', [
            'id' => $version->id,
        ]);
    }

    /**
     * Test that only project owner can update a version of his project.
     *
     * @return void
     */
    public function test_project_owner_can_delete_a_version_of_his_project(): void
    {
        $version = Version::factory()->create();

        $response =
            $this->actingAs($version->user)
                ->delete('/api/versions/'.$version->id);
        $response->assertSuccessful();
        $this->assertDatabaseMissing('versions', [
            'id' => $version->id,
        ]);
    }

    /**
     * Test that random users cannot add a version to a project.
     *
     * @return void
     */
    public function test_random_user_cannot_delete_a_version(): void
    {
        $version = Version::factory()->create();
        $user = User::factory()->create();

        $response =
            $this->actingAs($user)
                ->delete('/api/versions/'.$version->id);
        $response->assertStatus(403);
        $this->assertDatabaseHas('versions', [
            'id' => $version->id,
        ]);
    }
}
