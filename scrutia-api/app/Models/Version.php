<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Version extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'description',
    ];

    protected $hidden = ['project_id', 'user_id', 'updated_at'];


    protected $appends = ['upvotes', 'downvotes', 'user_vote'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
