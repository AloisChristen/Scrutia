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
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $user->favorites()->save($project);

        $response = $this->actingAs($user)->get("api/favorites");

        $response->assertSuccessful();
        $this->assertNotEmpty($response->getContent());
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

    /**
     * Test user can add a project on favorite only one time
     *
     * @return void
     */
    public function test_user_can_add_a_project_to_favorites_only_one_time(): void
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

    /**
     * Test user cannnot add a non existent project to his favorites
     *
     * @return void
     */
    public function test_user_cannot_add_non_existent_project_to_favorites(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post("/api/favorites");

        $response->assertNotFound();
        $this->assertDatabaseCount("project_user",0);
    }

    /**
     * Test user can delete a favorite
     *
     * @return void
     */
    public function test_user_can_delete_favorite(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $user->favorites()->save($project);
        $this->assertEquals(1, $user->favorites()->count());

        $response = $this->actingAs($user)->delete("/api/favorites/".$project->id);
        $user = User::find($user->id);
        $response->assertSuccessful();
        $this->assertEquals(0, $user->favorites()->count());
    }

    /**
     * Test user cannot delete a favorite that does not exist
     *
     * @return void
     */
    public function test_user_cannot_delete_favorite_of_non_existent_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $user->favorites()->save($project);
        $this->assertEquals(1, $user->favorites()->count());

        $response = $this->actingAs($user)->delete("/api/favorites/999");
        $user = User::find($user->id);

        $response->assertNotFound();
        $this->assertEquals(1, $user->favorites()->count());
    }
}
