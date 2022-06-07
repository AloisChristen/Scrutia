<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description'
    ];

    protected $appends = ['upvotes', 'downvotes', 'user_vote'];

    protected $hidden = ['question_id', 'user_id','updated_at'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
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
