<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_that_has_more_than_100_reputation_can_like_a_question(): void
    {
        $question = Question::factory()->create();
        $response =
            $this->actingAs($question->user)
                ->post('/api/questions/'. $question->id . '/like',[
                    "value" => 1
                ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas("likes",[
            "likeable_type" => "App\Models\Question",
            "likeable_id" => $question->id
        ]);
    }

    public function test_user_that_has_more_than_100_reputation_can_like_an_answer(): void
    {
        $answer = Answer::factory()->create();
        $response =
            $this->actingAs($answer->user)
                ->post('/api/answers/'. $answer->id . '/like',[
                    "value" => 1
                ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas("likes",[
            "likeable_type" => "App\Models\Answer",
            "likeable_id" => $answer->id
        ]);
    }
}
