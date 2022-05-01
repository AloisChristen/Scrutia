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
        $project = Project::create([
            "title" => "Test"
        ]);
        $project->user()->associate($user);
        $version = Version::create([
            'number' => 1,
            'status' => Status::IDEE,
            'description' => "Test",
        ]);
        $version->project()->associate($project);
        $version->user()->associate($project->user);

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
}
