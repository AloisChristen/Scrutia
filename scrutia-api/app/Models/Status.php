<?php

namespace App\Models;

use Illuminate\Support\Arr;

enum Status: string
{
    case IDEE = 'idee';
    case INITIATIVE = 'initiative';

    public static function getRandomValue(): string
    {
        return Arr::random(Status::cases());
    }
}
