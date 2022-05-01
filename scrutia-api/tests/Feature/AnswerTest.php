<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_answer_to_a_question(): void
    {

    }

    public function test_user_receive_5_reputation_after_answering_a_question(): void
    {

    }

    public function test_user_cannot_answer_to_a_question_with_bad_question_id(): void
    {

    }

    public function test_owner_can_update_answer(): void
    {

    }

    public function test_user_with_150_or_more_reputation_can_update_answer(): void
    {

    }

    public function test_user_with_less_than_150_reputation_cannot_update_answer(): void
    {

    }

    public function test_owner_can_delete_answer(): void
    {

    }

    public function test_user_with_200_or_more_reputation_can_delete_answer(): void
    {

    }

    public function test_user_with_less_than_200_reputation_cannot_delete_answer(): void
    {

    }
}
