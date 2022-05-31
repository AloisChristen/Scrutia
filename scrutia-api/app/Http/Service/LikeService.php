<?php

namespace App\Http\Service;

use App\Models\Likeable;
use App\Models\User;
use App\Models\Vote;

class LikeService
{
    public static function addVoteReputation(User $owner, Vote $vote, int $liker_id = null, Likeable $model = null, bool $modified = false): void
    {
        $liker = User::find($liker_id);
        switch($model){
            case Likeable::ANSWER:
            case Likeable::QUESTION:
                $UPVOTE_VALUE = 2;
                $DOWNVOTE_VALUE = -1;
                break;
            case Likeable::VERSION:
            case Likeable::PROJECT:
                $UPVOTE_VALUE = 5;
                $DOWNVOTE_VALUE = -2;
                break;
            default:
                return;
        }

        // If it's a modification, we have to remove old gains
        if($modified){
            if($vote == Vote::DOWNVOTE){
                $owner->reputation -= $UPVOTE_VALUE;
            }
            else if ($vote == Vote::UPVOTE){
                $owner->reputation -= $DOWNVOTE_VALUE;
            }
            $owner->save();
        }

        if($vote == Vote::UPVOTE){
            $owner->reputation += $UPVOTE_VALUE;
        }
        else if ($vote == Vote::DOWNVOTE){
            $owner->reputation += $DOWNVOTE_VALUE;
            $liker->reputation -= 1;
            $liker->save();
        }
        $owner->save();
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
