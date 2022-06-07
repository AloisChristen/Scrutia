<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    protected $appends = ['upvotes', 'downvotes', 'user_vote'];

    protected $hidden = ['version_id', 'user_id','updated_at'];



    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function getUpvotesAttribute(): int
    {
        return $this->likes()->where('value', 1)->count();
    }

    public function getDownvotesAttribute(): int
    {
        return $this->likes()->where('value', -1)->count();
    }

    public function getUserVoteAttribute(): Vote
    {
        $vote = Vote::UNVOTED;
        if(auth()->user() != null){
            foreach ($this->likes()->get() as $like){
                if($like->user->id == auth()->user()->id){
                    $vote = $like->value;
                }
            }
        }
        return $vote;
    }
}
