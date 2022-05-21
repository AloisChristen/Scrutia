<?php

namespace App\Http\Service;

use App\Models\Likeable;
use App\Models\User;

class LikeService
{
    public static function addVoteReputation(User $owner, int $vote, User $liker = null, Likeable $model = null, bool $modified = false): void
    {
        $UPVOTE_VALUE = 0;
        $DOWNVOTE_VALUE = 0;

        switch($model){
            case Likeable::ANSWER:
            case Likeable::QUESTION:
                $UPVOTE_VALUE = 2;
                $DOWNVOTE_VALUE = -1;
                break;
            case Likeable::VERSION:
                $UPVOTE_VALUE = 5;
                $DOWNVOTE_VALUE = -2;
                break;
        }

        // If it's a modification, we have to remove old gains
        if($modified){
            if($vote == -1){
                $owner->reputation -= $UPVOTE_VALUE;
            }
            else if ($vote == 1){
                $owner->reputation -= $DOWNVOTE_VALUE;
            }
        }

        if($vote == 1){
            $owner->reputation += $UPVOTE_VALUE;
        }
        else if ($vote == -1){
            $owner->reputation += $DOWNVOTE_VALUE;
            $liker->reputation += $DOWNVOTE_VALUE;
        }
    }

    public static function addNewQuestionReputation(User $project_owner): void
    {
        $project_owner->reputation += 5;
        $project_owner->save();
    }

    public static function addNewAnswerReputation(User $question_owner): void
    {
        $question_owner->reputation += 2;
        $question_owner->save();
    }
}