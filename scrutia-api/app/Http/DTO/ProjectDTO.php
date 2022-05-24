<?php

namespace App\Http\DTO;

use App\Models\Project;
use App\Models\Vote;

class ProjectDTO
{
    private $id;
    private $title;
    private $description;
    private $nb_upvotes = 0;
    private $nb_downvotes = 0;
    private $user_vote = 0;
    private $is_favorite = false;
    private $increase;
    private $tags;
    private $author;
    private $created_at;

    public function __construct(Project $project)
    {
        $this->id = $project->id;
        $last_version = $project->versions()->orderBy("number",'desc')->first();
        $this->title = $project->title;
        if($last_version != null){
            $this->description = $last_version->description;
        }

        foreach($project->versions()->get() as $version) {
            foreach ($version->likes()->get() as $like) {
                if ($like->value == Vote::UPVOTE) {
                    $this->nb_upvotes += 1;
                    if (auth()->user() != null && $like->user->id == auth()->user()->id) {
                        $this->user_vote = Vote::UPVOTE;
                    }
                } else if ($like->value == Vote::DOWNVOTE) {
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

    /**
     * @return mixed
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle(): mixed
    {
        return $this->title;
    }

    /**
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function getDescription(): mixed
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getNbUpvotes(): int
    {
        return $this->nb_upvotes;
    }

    /**
     * @return int
     */
    public function getNbDownvotes(): int
    {
        return $this->nb_downvotes;
    }

    /**
     * @return Vote|int
     */
    public function getUserVote(): int|Vote
    {
        return $this->user_vote;
    }

    /**
     * @return bool
     */
    public function isFavorite(): bool
    {
        return $this->is_favorite;
    }

    /**
     * @return mixed
     */
    public function getIncrease()
    {
        return $this->increase;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getTags(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
    {
        return $this->tags;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt(): mixed
    {
        return $this->created_at;
    }


}
