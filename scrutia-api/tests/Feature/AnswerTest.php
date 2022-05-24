<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_with_less_than_0_reputation_can_create_only_10_answers_per_day(): void
    {

        $question = Question::factory()->create();
        $user = User::factory()->create([
            "reputation" => -100
        ]);
        $answer_attributes = [
            "title" => $this->faker->word(),
            "description" => $this->faker->text(),
            "question_id" => $question->id
        ];

        $response = $this->actingAs($user)->post('/api/answers', $answer_attributes);
        $response->assertCreated();
        $response = $this->actingAs($user)->post('/api/answers', $answer_attributes);
        $response->assertCreated();
        $response = $this->actingAs($user)->post('/api/answers', $answer_attributes);
        $response->assertCreated();
        $response = $this->actingAs($user)->post('/api/answers', $answer_attributes);
        $response->assertCreated();
        $response = $this->actingAs($user)->post('/api/answers', $answer_attributes);
        $response->assertCreated();
        $response = $this->actingAs($user)->post('/api/answers', $answer_attributes);
        $response->assertCreated();
        $response = $this->actingAs($user)->post('/api/answers', $answer_attributes);
        $response->assertCreated();
        $response = $this->actingAs($user)->post('/api/answers', $answer_attributes);
        $response->assertCreated();
        $response = $this->actingAs($user)->post('/api/answers', $answer_attributes);
        $response->assertCreated();
        $response = $this->actingAs($user)->post('/api/answers', $answer_attributes);
        $response->assertCreated();
        $response = $this->actingAs($user)->post('/api/answers', $answer_attributes);
        $response->assertStatus(403);
    }

    public function test_owner_receive_2_reputation_after_user_answers_a_question(): void
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
            "reputation" => 102
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

    public function test_user_with_200_or_more_reputation_can_update_answer(): void
    {
        $answer = Answer::factory()->create();
        $user = User::factory()->create([
           "reputation" => 200
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

    public function test_user_with_less_than_200_reputation_cannot_update_answer(): void
    {
        $answer = Answer::factory()->create();
        $user = User::factory()->create([
            "reputation" => 199
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
