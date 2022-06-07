<?php

namespace App\Http\Service;

use App\Models\Project;
use App\Models\Tag;
use App\Models\Vote;
use Illuminate\Support\Str;

class ProjectService
{
    public static function createAndAttachTags($project, array $tags = null): void
    {
        //check if tags are null
        if ($tags == null) {
            $tags = [];
        }
        $project->tags()->detach();
        foreach ($tags as $tag) {
            $current_tag = Tag::where('title', $tag['title'])->firstOr(function () use ($tag) {
                return Tag::create([
                    'title' => Str::upper($tag['title']),
                ]);
            });

            $project->tags()->save($current_tag);
        }
    }
}
