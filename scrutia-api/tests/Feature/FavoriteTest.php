<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Test unauthenticated users cannot use Favorite Endpoint
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_favorites_endpoint(): void
    {
        $project = Project::factory()->create();
        $response = $this->post("api/favorites",[
            "project_id" => $project->id
        ]);

        $response->assertRedirect("/api/login");
        $this->assertDatabaseMissing("project_user",[
           "project_id" => $project->id
        ]);
    }

    /**
     * Test user can get his favorites
     *
     * @return void
     */
    public function test_user_can_list_his_favorites(): void
    {

    }

    /**
     * Test unauthenticated users cannot add a project to favorites
     *
     * @return void
     */
    public function test_user_can_add_project_to_favorites(): void
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post("/api/favorites", [
            "project_id" => $project->id
        ]);

        $response->assertCreated();
        $this->assertDatabaseCount("project_user", 1);
    }

    public function test_user_can_add_a_project_to_favorites_only_one_time()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post("/api/favorites", [
            "project_id" => $project->id
        ]);

        $response->assertCreated();

        $response = $this->actingAs($user)->post("/api/favorites", [
            "project_id" => $project->id
        ]);

        $response->assertCreated();
        $this->assertDatabaseCount("project_user", 1);
    }

    public function test_user_cannot_add_non_existent_project_to_favorites()
    {

    }

    public function test_user_can_delete_favorite(): void
    {

    }

    public function test_user_cannot_delete_favorite_of_non_existent_project(): void
    {

    }
}
