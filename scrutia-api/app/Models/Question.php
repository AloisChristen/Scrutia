<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function versions(): BelongsToMany
    {
        return $this->belongsToMany(Version::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
