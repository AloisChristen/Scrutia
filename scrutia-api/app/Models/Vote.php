<?php

namespace App\Models;


enum Vote: int
{
    case UPVOTE = 1;
    case DOWNVOTE = -1;
    case UNVOTED = 0;
}
