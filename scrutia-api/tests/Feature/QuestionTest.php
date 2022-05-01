<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Question;
use App\Models\Status;
use App\Models\User;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_question(): void
    {
        $user = User::factory()->create();
        $project = Project::create([
            "title" => "Test"
        ]);
        $project->user()->associate($user);
        $version = Version::create([
            'number' => 1,
            'status' => Status::INITIATIVE,
            'description' => "Test",
            'project_id' => $project->id,
            'user_id' => $project->user->id,
        ]);
        $version->project()->associate($project);

        $response =
            $this->actingAs($user)
            ->post('/api/questions', [
                "title" => "test",
                "description" => "Lorem Ipsum",
                "project_id" => $version->project->id,
                "version_number" => $version->number,
            ]);
        $response->assertCreated();

    }
}
