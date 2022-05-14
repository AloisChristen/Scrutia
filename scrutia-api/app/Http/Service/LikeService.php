<?php

namespace App\Http\Service;

use App\Models\User;

class LikeService
{
    public static function addVoteReputation(User $owner, int $vote, User $liker = null, bool $modified = false): void
    {
        $UPVOTE_VALUE = 2;
        $DOWNVOTE_VALUE = -1;

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
}
