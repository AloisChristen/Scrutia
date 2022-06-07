<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status'
    ];

    protected $casts = [
        'status' => Status::class
    ];

    protected $hidden = [
        'user_id',
        'updated_at',
        'pivot',
        'user'
    ];

    protected $appends = [
        'upvotes',
        'downvotes',
        'is_favorite',
        'user_vote',
        'last_description',
        'author',
        'performance'
    ];


    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function versions(): HasMany
    {
        return $this->hasMany(Version::class);
    }

    /**
     * @return morphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Filter projects search by start date.
     * @param Builder $query
     * @param $date
     * @return Builder
     */
    public function scopeStartDate(Builder $query, $date = null): Builder
    {
        return $query->where('created_at', '>=', Carbon::parse($date));
    }

    /**
     * Filter projects search by end date.
     * @param Builder $query
     * @param $date
     * @return Builder
     */
    public function scopeEndDate(Builder $query, $date): Builder
    {
        return $query->where('created_at', '<=', Carbon::parse($date));
    }


    /**
     * Filter projects search by title.
     * @param Builder $query
     * @param $title
     * @return Builder
     */
    public function scopeTitle(Builder $query, $title = null): Builder
    {
        return $query->where('title', 'like', "%$title%");
    }

    /**
     * Filter projects search by tags.
     * @param Builder $query
     * @param array $tags
     * @return Builder
     */
    public function scopeTags(Builder $query, $tags = null): Builder
    {
        return $query->whereHas('tags', function (Builder $query) use ($tags) {
            $query->whereIn('title', [$tags]);
        });
    }

    /**
     * Filter projects search by tags.
     * @param Builder $query
     * @param null $status
     * @return Builder
     */
    public function scopeStatus(Builder $query, $status = null): Builder
    {
        return $query->where('status', $status);

    }

    public function getLastDescriptionAttribute(): string|null
    {
        $description = null;
        $version = $this->versions()->orderBy('number','desc')->first();
        if($version != null){
            $description = $version->description;
        }
        return $description;
    }

    public function getIsFavoriteAttribute(): bool
    {
        $is_favorite = false;
        if(auth()->user() != null){
            foreach($this->favorites()->get() as $favorite){
                if($favorite->id == auth()->user()->id){
                    $is_favorite = true;
                }
            }
        }
        return $is_favorite;
    }

    public function getPerformanceAttribute(): string
    {
        $perf = '0%';
        $yesterday_count = $this->likes()->whereDate('created_at', '<=', Carbon::yesterday())->count();
        if($yesterday_count > 0){
            $perf = ($this->likes()->whereDate('created_at', '<=', Carbon::today())->count() /
                    $yesterday_count) * 100 . '%';
        }
        return $perf;
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

    public function getAuthorAttribute(): string
    {
        return $this->user->username;
    }
}
