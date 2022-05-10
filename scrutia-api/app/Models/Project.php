<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    /**
     * Get the project's newest version.
     */
    public function newestVersion()
    {
        return $this->hasOne(Version::class)->ofMany('number', 'max');
    }

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
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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

}
