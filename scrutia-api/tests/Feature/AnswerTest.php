<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_answer_to_a_question(): void
    {
        // As the database is refreshing, we're creating the ressources that we need to ask a question
        $question = Question::factory()->create();

        // Making the request to the endpoint
        $response =
            $this->actingAs($question->user)
                ->post('/api/answers', [
                    "title" => "test",
                    "description" => "Lorem Ipsum",
                    "question_id" => $question->id,
                ]);

        $response->assertCreated();
        $this->assertEquals('"Created"', $response->getContent());
        $this->assertDatabaseHas("answers", [
            "title" => "test",
            "description" => "Lorem Ipsum"
        ]);
    }

    public function test_user_receive_5_reputation_after_answering_a_question(): void
    {
        $question = Question::factory()->create();

        // Making the request to the endpoint
        $response =
            $this->actingAs($question->user)
                ->post('/api/answers', [
                    "title" => "test",
                    "description" => "Lorem Ipsum",
                    "question_id" => $question->id,
                ]);

        $response->assertCreated();
        $this->assertDatabaseHas("users", [
            "id" => $question->user->id,
            "reputation" => 105
        ]);
    }

    public function test_user_cannot_answer_to_a_question_with_bad_question_id(): void
    {
        $user = User::factory()->create();
        $response =
            $this->actingAs($user)
                ->post('/api/answers', [
                    "title" => "test",
                    "description" => "Lorem Ipsum",
                    "question_id" => 999,
                ]);
        $response->assertNotFound();
        $this->assertDatabaseMissing("answers", [
            "title" => "test",
            "description" => "Lorem Ipsum"
        ]);
    }

    public function test_owner_can_update_answer(): void
    {
        $answer = Answer::factory()->create();
        $response =
            $this->actingAs($answer->user)
                ->put('/api/answers/'.$answer->id, [
                    "title" => "test",
                    "description" => "Lorem Ipsum",
                ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas("answers", [
            "id" => $answer->id,
            "title" => "test",
            "description" => "Lorem Ipsum"
        ]);
    }

    public function test_user_with_150_or_more_reputation_can_update_answer(): void
    {
        $answer = Answer::factory()->create();
        $user = User::factory()->create([
           "reputation" => 150
        ]);
        $response =
            $this->actingAs($user)
                ->put('/api/answers/'.$answer->id, [
                    "title" => "test",
                    "description" => "Lorem Ipsum",
                ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas("answers", [
            "id" => $answer->id,
            "title" => "test",
            "description" => "Lorem Ipsum"
        ]);
    }

    public function test_user_with_less_than_150_reputation_cannot_update_answer(): void
    {
        $answer = Answer::factory()->create();
        $user = User::factory()->create([
            "reputation" => 100
        ]);
        $response =
            $this->actingAs($user)
                ->put('/api/answers/'.$answer->id, [
                    "title" => "test",
                    "description" => "Lorem Ipsum",
                ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas("answers", [
            "id" => $answer->id,
            "title" => $answer->title,
            "description" => $answer->description
        ]);
    }

    public function test_owner_can_delete_answer(): void
    {
        $answer = Answer::factory()->create();
        $response =
            $this->actingAs($answer->user)
                ->delete('/api/answers/'.$answer->id);

        $response->assertSuccessful();
        $this->assertDatabaseMissing("answers", [
            "id" => $answer->id,
        ]);
    }

    public function test_user_with_200_or_more_reputation_can_delete_answer(): void
    {
        $answer = Answer::factory()->create();
        $user = User::factory()->create([
            "reputation" => 200
        ]);
        $response =
            $this->actingAs($user)
                ->delete('/api/answers/'.$answer->id);

        $response->assertSuccessful();
        $this->assertDatabaseMissing("answers", [
            "id" => $answer->id,
        ]);
    }

    public function test_user_with_less_than_200_reputation_cannot_delete_answer(): void
    {
        $answer = Answer::factory()->create();
        $user = User::factory()->create([
            "reputation" => 150
        ]);
        $response =
            $this->actingAs($user)
                ->delete('/api/answers/'.$answer->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas("answers", [
            "id" => $answer->id,
        ]);
    }
}
