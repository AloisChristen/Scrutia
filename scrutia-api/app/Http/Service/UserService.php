<?php

namespace App\Http\Service;

use App\Models\User;

class UserService
{
    public static function addQuestionReputation(User $user): void
    {
        $user->reputation += 10;
        $user->save();
    }

    public static function addAnswerReputation(User $user): void
    {
        $user->reputation += 5;
        $user->save();
    }
}
