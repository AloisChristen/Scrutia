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

    public static function addLikesAttributes(Project $project): void
    {
        foreach($project->versions as $version){
            foreach($version->likes as $like){
                if($like->value == Vote::UPVOTE){
                    $project->nb_total_upvotes += 1;
                    $version->nb_upvotes += 1;
                    if(auth()->user() != null && $like->user->id == auth()->user()->id){
                        $version->user_vote = Vote::UPVOTE;
                    }
                }
                else if($like->value == Vote::DOWNVOTE){
                    $project->nb_total_downvotes += 1;
                    $version->nb_downvotes += 1;
                    if(auth()->user() != null && $like->user->id == auth()->user()->id){
                        $version->user_vote = Vote::DOWNVOTE;
                    }
                }
            }
            foreach($version->questions as $question) {
                self::getLike($question);
                foreach($question->answers as $answer) {
                    self::getLike($answer);
                }
            }
        }
    }

    /**
     * @param mixed $likeable
     * @return void
     */
    private static function getLike(mixed $likeable): void
    {
        foreach ($likeable->likes as $like) {
            if ($like->value == Vote::UPVOTE) {
                $likeable->nb_upvotes += 1;
                if (auth()->user() != null && $like->user->id == auth()->user()->id) {
                    $likeable->user_vote = Vote::UPVOTE;
                }
            } else if ($like->value == Vote::DOWNVOTE) {
                $likeable->nb_downvotes += 1;
                if (auth()->user() != null && $like->user->id == auth()->user()->id) {
                    $likeable->user_vote = Vote::DOWNVOTE;
                }
            }
        }
    }
}
