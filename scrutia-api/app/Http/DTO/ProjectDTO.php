<?php

namespace App\Http\DTO;

use App\Models\Project;
use App\Models\Vote;

class ProjectDTO
{
    private $title;
    private $description;
    private $nb_upvotes = 0;
    private $nb_downvotes = 0;
    private $user_vote = 0;
    private $is_favorite = false;
    private $augmentation;
    private $tags;
    private $author;
    private $created_at;

    public function __construct(Project $project)
    {
        $last_version = $project->versions()->where("number", $project->versions()->max("number"))->first();
        $this->title = $project->title;
        $this->description = $last_version->description;

        foreach($project->versions() as $version) {
            foreach ($version->likes() as $like) {
                if ($like->value == Vote::UPVOTE) {
                    $this->nb_upvotes += 1;
                    if (auth()->user() != null && $like->user->id == auth()->user()->id) {
                        $this->user_vote = Vote::UPVOTE;
                    }
                } else if ($like->value == V§ote::DOWNVOTE) {
                    $this->nb_downvotes += 1;
                    if (auth()->user() != null && $like->user->id == auth()->user()->id) {
                        $version->user_vote = Vote::DOWNVOTE;
                    }
                }
            }
        }

        if(auth()->user() != null){
            foreach($project->favorites() as $favorite){
                if($favorite->user->id == auth()->user()->id){
                    $this->is_favorite = true;
                }
            }
        }

        $tags = $project->tags()->get();
        $this->tags = $tags->map(function ($tag){
            return [
                "title" => $tag->title
            ];
        });

        $this->author = $project->user->username;

        $this->created_at = $project->created_at;
    }

    public function __toArray(){
        return call_user_func('get_object_vars', $this);
    }
}
