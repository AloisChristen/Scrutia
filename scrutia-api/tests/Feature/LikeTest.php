<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Like;
use App\Models\Question;
use App\Models\User;
use App\Models\Version;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthenticated user cannot like a question
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_like_a_question(): void
    {
        $question = Question::factory()->create();
        $response = $this
                ->post('/api/questions/'. $question->id . '/like',[
                    "value" => 1
                ]);
        $response->assertRedirect("api/login");
        $this->assertDatabaseMissing("likes",[
            "likeable_type" => "App\Models\Question",
            "likeable_id" => $question->id,
            "user_id" => $question->user->id
        ]);
    }

    /**
     * Test that user with more or equal than 50 reputation can like a question
     *
     * @return void
     */
    public function test_user_that_has_more_than_50_reputation_can_like_a_question(): void
    {
        $question = Question::factory()->create();
        $response =
            $this->actingAs($question->user)
                ->post('/api/questions/'. $question->id . '/like',[
                    "value" => 1
                ]);
        $response->assertSuccessful();
        $this->assertEquals('"Liked"', $response->getContent());
        $this->assertDatabaseHas("likes",[
            "likeable_type" => "App\Models\Question",
            "likeable_id" => $question->id
        ]);
    }

    /**
     * Test that user with less than 50 reputation cannot like a question
     *
     * @return void
     */
    public function test_user_that_has_less_than_50_reputation_cannot_like_a_question(): void
    {
        $question = Question::factory()->create();
        $user = User::factory()->create([
            "reputation" => 49
        ]);
        $response =
            $this->actingAs($user)
                ->post('/api/questions/'. $question->id . '/like',[
                    "value" => 1
                ]);
        $response->assertStatus(403);
        $this->assertDatabaseMissing("likes",[
            "likeable_type" => "App\Models\Question",
            "likeable_id" => $question->id
        ]);
    }

    /**
     * Test that user can only have one like per question
     *
     * @return void
     */
    public function test_user_can_only_have_one_like_per_question(): void
    {
        $question = Question::factory()->create();
        $this->actingAs($question->user)
                ->post('/api/questions/'. $question->id . '/like',[
                    "value" => 1
                ]);
        $this->assertEquals(1, Like::where("likeable_id",$question->id)->where("likeable_type", "App\Models\Question")->where("user_id", $question->user->id)->count());
        $this->assertDatabaseHas("likes",[
            "likeable_type" => "App\Models\Question",
            "likeable_id" => $question->id,
            "user_id" => $question->user->id,
            "value" => 1
        ]);
        $response =
            $this->actingAs($question->user)
                ->post('/api/questions/'. $question->id . '/like',[
                    "value" => -1
                ]);
        $this->assertEquals(1, Like::where("likeable_id",$question->id)->where("likeable_type", "App\Models\Question")->where("user_id", $question->user->id)->count());

        $response->assertSuccessful();
        $this->assertEquals('"Liked"', $response->getContent());
        $this->assertDatabaseHas("likes",[
            "likeable_type" => "App\Models\Question",
            "likeable_id" => $question->id,
            "user_id" => $question->user->id,
            "value" => -1
        ]);
    }

    /**
     * Test that upvote give 2 reputation to question's owner
     *
     * @return void
     */
    public function test_upvote_give_2_reputation_to_question_owner(): void
    {
        $question = Question::factory()->create();
        $user = User::factory()->create();

        $response =
            $this->actingAs($user)
                ->post('/api/questions/'. $question->id . '/like',[
                    "value" => VOTE::UPVOTE->value
                ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas("users",[
            "id" => $question->user->id,
            "reputation" => 102
        ]);
    }

    /**
     * Test that downvote remove 1 reputation to question's owner
     *
     * @return void
     */
    public function test_downvote_remove_1_reputation_to_question_owner(): void
    {
        $question = Question::factory()->create();
        $user = User::factory()->create();

        $response =
            $this->actingAs($user)
                ->post('/api/questions/'. $question->id . '/like',[
                    "value" => VOTE::DOWNVOTE->value
                ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas("users",[
            "id" => $question->user->id,
            "reputation" => 99
        ]);
    }

    /**
     * Test unauthenticated user cannot like a question
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_like_an_answer(): void
    {
        $answer = Answer::factory()->create();
        $response = $this
            ->post('/api/answers/'. $answer->id . '/like',[
                "value" => 1
            ]);
        $response->assertRedirect("api/login");
        $this->assertDatabaseMissing("likes",[
            "likeable_type" => "App\Models\Answer",
            "likeable_id" => $answer->id,
            "user_id" => $answer->user->id
        ]);
    }

    /**
     * Test that user with more or equal than 50 reputation can like an answer
     *
     * @return void
     */
    public function test_user_that_has_more_than_50_reputation_can_like_an_answer(): void
    {
        $answer = Answer::factory()->create();
        $response =
            $this->actingAs($answer->user)
                ->post('/api/answers/'. $answer->id . '/like',[
                    "value" => 1
                ]);
        $response->assertSuccessful();
        $this->assertEquals('"Liked"', $response->getContent());
        $this->assertDatabaseHas("likes",[
            "likeable_type" => "App\Models\Answer",
            "likeable_id" => $answer->id
        ]);
    }

    /**
     * Test that user with less than 50 reputation cannot like an answer
     *
     * @return void
     */
    public function test_user_that_has_less_than_50_reputation_cannot_like_an_answer(): void
    {
        $answer = Answer::factory()->create();
        $user = User::factory()->create([
            "reputation" => 49
        ]);
        $response =
            $this->actingAs($user)
                ->post('/api/answers/'. $answer->id . '/like',[
                    "value" => 1
                ]);
        $response->assertStatus(403);
        $this->assertDatabaseMissing("likes",[
            "likeable_type" => "App\Models\Answer",
            "likeable_id" => $answer->id
        ]);
    }

    /**
     * Test that user can only have one like per answer
     *
     * @return void
     */
    public function test_user_can_only_have_one_like_per_answer(): void
    {
        $answer = Answer::factory()->create();

        $this->actingAs($answer->user)
            ->post('/api/answers/'. $answer->id . '/like',[
                "value" => 1
            ]);

        $this->assertEquals(1,
            Like::where("likeable_id",$answer->id)
                ->where("likeable_type", "App\Models\Answer")
                ->where("user_id", $answer->user->id)
                ->count()
        );
        $this->assertDatabaseHas("likes",[
            "likeable_type" => "App\Models\Answer",
            "likeable_id" => $answer->id,
            "user_id" => $answer->user->id,
            "value" => 1
        ]);

        $response =
            $this->actingAs($answer->user)
                ->post('/api/answers/'. $answer->id . '/like',[
                    "value" => -1
                ]);

        $this->assertEquals(1,
            Like::where("likeable_id",$answer->id)
            ->where("likeable_type", "App\Models\Answer")
            ->where("user_id", $answer->user->id)->count()
        );

        $response->assertSuccessful();
        $this->assertEquals('"Liked"', $response->getContent());
        $this->assertDatabaseHas("likes",[
            "likeable_type" => "App\Models\Answer",
            "likeable_id" => $answer->id,
            "user_id" => $answer->user->id,
            "value" => -1
        ]);
    }

    /**
     * Test that upvote give 2 reputation to answer's owner
     *
     * @return void
     */
    public function test_upvote_give_2_reputation_to_answer_owner(): void
    {
        $answer = Answer::factory()->create();
        $user = User::factory()->create();

        $response =
            $this->actingAs($user)
                ->post('/api/answers/'. $answer->id . '/like',[
                    "value" => VOTE::UPVOTE->value
                ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas("users",[
            "id" => $answer->user->id,
            "reputation" => 102
        ]);
    }

    /**
     * Test that downvote remove 1 reputation to question's owner
     *
     * @return void
     */
    public function test_downvote_remove_1_reputation_to_answer_owner(): void
    {
        $answer = Answer::factory()->create();
        $user = User::factory()->create();

        $response =
            $this->actingAs($user)
                ->post('/api/answers/'. $answer->id . '/like',[
                    "value" => VOTE::DOWNVOTE->value
                ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas("users",[
            "id" => $answer->user->id,
            "reputation" => 99
        ]);
    }

    /**
     * Test unauthenticated user cannot like a version
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_like_a_version(): void
    {
        $version = Version::factory()->create();
        $response = $this
            ->post('/api/versions/'. $version->id . '/like',[
                "value" => 1
            ]);
        $response->assertRedirect("api/login");
        $this->assertDatabaseMissing("likes",[
            "likeable_type" => "App\Models\Version",
            "likeable_id" => $version->id,
            "user_id" => $version->user->id
        ]);
    }

    /**
     * Test that user with more or equal than 50 reputation can like a version
     *
     * @return void
     */
    public function test_user_that_has_more_than_50_reputation_can_like_a_version(): void
    {
        $question = Version::factory()->create();
        $response =
            $this->actingAs($question->user)
                ->post('/api/versions/'. $question->id . '/like',[
                    "value" => 1
                ]);

        $response->assertSuccessful();
        $this->assertEquals('"Liked"', $response->getContent());
        $this->assertDatabaseHas("likes",[
            "likeable_type" => "App\Models\Version",
            "likeable_id" => $question->id
        ]);
    }

    /**
     * Test that user with less than 50 reputation cannot like a version
     *
     * @return void
     */
    public function test_user_that_has_less_than_50_reputation_cannot_like_a_version(): void
    {
        $question = Version::factory()->create();
        $user = User::factory()->create([
            "reputation" => 49
        ]);

        $response =
            $this->actingAs($user)
                ->post('/api/versions/'. $question->id . '/like',[
                    "value" => 1
                ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing("likes",[
            "likeable_type" => "App\Models\Version",
            "likeable_id" => $question->id
        ]);
    }

    /**
     * Test that user can only have one like per version
     *
     * @return void
     */
    public function test_user_can_only_have_one_like_per_version(): void
    {
        $version = Version::factory()->create();
        $this->actingAs($version->user)
            ->post('/api/versions/'. $version->id . '/like',[
                "value" => 1
            ]);
        $this->assertEquals(1, Like::where("likeable_id",$version->id)->where("likeable_type", "App\Models\Version")->where("user_id", $version->user->id)->count());
        $this->assertDatabaseHas("likes",[
            "likeable_type" => "App\Models\Version",
            "likeable_id" => $version->id,
            "user_id" => $version->user->id,
            "value" => 1
        ]);
        $response =
            $this->actingAs($version->user)
                ->post('/api/versions/'. $version->id . '/like',[
                    "value" => -1
                ]);
        $this->assertEquals(1, Like::where("likeable_id",$version->id)->where("likeable_type", "App\Models\Version")->where("user_id", $version->user->id)->count());

        $response->assertSuccessful();
        $this->assertEquals('"Liked"', $response->getContent());
        $this->assertDatabaseHas("likes",[
            "likeable_type" => "App\Models\Version",
            "likeable_id" => $version->id,
            "user_id" => $version->user->id,
            "value" => -1
        ]);
    }

    /**
     * Test that upvote give 5 reputation to project's owner
     *
     * @return void
     */
    public function test_upvote_give_5_reputation_to_project_owner(): void
    {
        $version = Version::factory()->create();
        $user = User::factory()->create();

        $response =
            $this->actingAs($user)
                ->post('/api/versions/'. $version->id . '/like',[
                    "value" => VOTE::UPVOTE->value
                ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas("users",[
            "id" => $version->user->id,
            "reputation" => 105
        ]);
    }

    /**
     * Test that downvote remove 1 reputation to question's owner
     *
     * @return void
     */
    public function test_downvote_remove_2_reputation_to_version_owner(): void
    {
        $version = Version::factory()->create();
        $user = User::factory()->create();

        $response =
            $this->actingAs($user)
                ->post('/api/versions/'. $version->id . '/like',[
                    "value" => VOTE::DOWNVOTE->value
                ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas("users",[
            "id" => $version->user->id,
            "reputation" => 98
        ]);
    }

    /**
     * Test that downvote remove 1 reputation to question's owner
     *
     * @return void
     */
    public function test_downvote_remove_1_reputation_to_user(): void
    {
        $version = Version::factory()->create();
        $user = User::factory()->create();

        $response =
            $this->actingAs($user)
                ->post('/api/versions/'. $version->id . '/like',[
                    "value" => VOTE::DOWNVOTE->value
                ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas("users",[
            "id" => $user->id,
            "reputation" => 99
        ]);
    }
}
