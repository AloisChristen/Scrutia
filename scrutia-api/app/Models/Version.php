<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Version extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => Status::class
    ];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }
}
