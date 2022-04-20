<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends Model
{
    use HasFactory;

    public function versions(): BelongsToMany
    {
        return $this->belongsToMany(Version::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
