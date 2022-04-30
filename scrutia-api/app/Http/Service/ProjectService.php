<?php

namespace App\Http\Service;

use App\Models\Tag;

class ProjectService
{
    public static function createAndAttachTags($project, $tags): void
    {
        $project->tags()->detach();

        foreach($tags as $tag){
            $current_tag = Tag::where('title', $tag->title)->firstOr(function() use ($tag) {
                return Tag::create([
                    'title' => $tag->title,
                ]);
            });

            $project->tags()->save($current_tag);
        }
    }
}
