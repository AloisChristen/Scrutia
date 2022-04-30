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
        $project = Project::factory()->create();
        $version = Version::create([
            'number' => 1,
            'status' => Status::INITIATIVE,
            'description' => $this->faker->text(),
            'project_id' => $project->id,
            'user_id' => $project->user->id,
        ]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->post('/api/questions', [
            "title" => $this->faker->words(10),
            "description" => $this->faker->sentences(),
            "project_id" => $project->id,
            "version_number" => $version->number,
        ]);

        $response->assertCreated();

    }
}
