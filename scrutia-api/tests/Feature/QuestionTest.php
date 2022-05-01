<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Question;
use App\Models\Status;
use App\Models\User;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_question(): void
    {
        // As the database is refreshing, we're creating the ressources that we need to ask a question
        $user = User::factory()->create();
        $version = Version::factory()->create();

        // Making the request to the endpoint
        $response =
            $this->actingAs($user)
            ->post('/api/questions', [
                "title" => "test",
                "description" => "Lorem Ipsum",
                "project_id" => $version->project->id,
                "version_number" => $version->number,
            ]);

        $response->assertCreated();
        $this->assertEquals('"Created"', $response->getContent());
        $this->assertDatabaseHas("questions", [
            "title" => "test",
            "description" => "Lorem Ipsum"
        ]);
    }

    public function test_user_cannot_create_question_with_wrong_project_id(): void
    {
        // As the database is refreshing, we're creating the ressources that we need to ask a question
        $user = User::factory()->create();

        // Making the request to the endpoint
        $response =
            $this->actingAs($user)
                ->post('/api/questions', [
                    "title" => "test",
                    "description" => "Lorem Ipsum",
                    "project_id" => 2,
                    "version_number" => 2,
                ]);

        $response->assertNotFound();
        $this->assertNotEmpty($response->getContent());
        $this->assertDatabaseMissing("questions", [
            "title" => "test",
            "description" => "Lorem Ipsum"
        ]);
    }

    public function test_user_cannot_create_question_with_wrong_version_number(): void
    {
        // As the database is refreshing, we're creating the ressources that we need to ask a question
        $user = User::factory()->create();
        $project = Project::factory()->create();


        // Making the request to the endpoint
        $response =
            $this->actingAs($user)
                ->post('/api/questions', [
                    "title" => "test",
                    "description" => "Lorem Ipsum",
                    "project_id" => $project->id,
                    "version_number" => 999,
                ]);

        $response->assertNotFound();
        $this->assertNotEmpty($response->getContent());
        $this->assertDatabaseMissing("questions", [
            "title" => "test",
            "description" => "Lorem Ipsum"
        ]);
    }

    public function test_owner_can_update_question(): void
    {
        // As the database is refreshing, we're creating the ressources that we need to ask a question
        $question = Question::factory()->create();
        // Making the request to the endpoint
        $response =
            $this->actingAs($question->user)
                ->put('/api/questions/'. $question->id, [
                    "title" => "string",
                    "description" => "Ceci est un string",
                ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas("questions", [
            "id" => $question->id,
            "title" => "string",
            "description" => "Ceci est un string"
        ]);
    }

    public function test_random_user_cannot_update_question(): void
    {
        // As the database is refreshing, we're creating the ressources that we need to ask a question
        $question = Question::factory()->create();
        $user = User::factory()->create();
        // Making the request to the endpoint
        $response =
            $this->actingAs($user)
                ->put('/api/questions/'. $question->id, [
                    "title" => "string",
                    "description" => "Ceci est un string",
                ]);

        $response->assertStatus(403);
        $this->assertNotEmpty($response->getContent());
        $this->assertDatabaseHas("questions", [
            "id" => $question->id,
            "title" => $question->title,
            "description" => $question->description
        ]);
    }

    public function test_owner_can_delete_question(): void
    {
        // As the database is refreshing, we're creating the ressources that we need to ask a question
        $question = Question::factory()->create();

        // Making the request to the endpoint
        $response =
            $this->actingAs($question->user)
                ->delete('/api/questions/'. $question->id);

        $response->assertSuccessful();
        $this->assertEquals('"Deleted"', $response->getContent());
        $this->assertDatabaseMissing("questions", [
            "id" => $question->id
        ]);
    }

    public function test_random_user_cannot_delete_question(): void
    {
        // As the database is refreshing, we're creating the ressources that we need to ask a question
        $question = Question::factory()->create();
        $user = User::factory()->create();

        // Making the request to the endpoint
        $response =
            $this->actingAs($user)
                ->delete('/api/questions/'. $question->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas("questions", [
            "id" => $question->id
        ]);
    }
}
