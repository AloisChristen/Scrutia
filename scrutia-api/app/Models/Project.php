<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function versions(): HasMany
    {
        return $this->hasMany(Version::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeEndDate(Builder $query, $date): Builder
    {
        return $query->where('created_at', '<=', Carbon::parse($date));
    }

    public function scopeStartDate(Builder $query, $date = null): Builder
    {
        error_log('scopeStartDate with date : ' + $date);

        return $query->where('created_at', '>=', Carbon::parse($date));
    }

    public function scopeTags(Builder $query, $tags): Builder
    {
        error_log('scopeTags with tags : ' + $tags);

        return $query->whereHas('tags', function (Builder $query) use ($tags) {
            $query->whereIn('title', $tags);
        });
    }

    public function scopeContent(Builder $query, $content): Builder
    {
        return $query->where('title', 'like', "%{$content}%");
    }
}
