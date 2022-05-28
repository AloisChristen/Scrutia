<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LikeRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Service\LikeService;
use App\Http\Service\ProjectService;
use App\Models\Like;
use App\Models\Likeable;
use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use App\Models\Version;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    /**
     * Display projects based on filters.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $projects = new Collection();
        $search = QueryBuilder::for(Project::class)
            ->allowedFilters([
                AllowedFilter::scope('startDate'),
                AllowedFilter::scope('endDate'),
                AllowedFilter::scope('title'),
                AllowedFilter::scope('tags'),
                AllowedFilter::scope('content'),
                AllowedFilter::scope('status'),
            ])->get();

        foreach ($search as $project){
            $projects->push([
                "id" => $project->id,
                "title" => $project->title,
                "description" => $project->lastVersionDescription(),
                "author" => $project->user->username,
                "upvotes" => $project->votes(Vote::UPVOTE),
                "downvotes" => $project->votes(Vote::DOWNVOTE),
                "is_favorite" => $project->isFavorite(),
                "increase" => $project->increase(),
                "created_at" => $project->created_at,
                "tags" => $project->tags()->get()->map(function ($tag) {
                    return $tag->title;
                }),
            ]);
        }
        return response()->json($projects->paginate());
    }

    /**
     * Return the specific resource
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $project = Project::with([
            "versions.questions.answers",
            "tags.title"
        ])->find($id);

        if($project == null){
            return response()->json(["message" => "Not Found", "errors" => [
                "id" => "Project does not exist."
            ]], 404);
        }
        ProjectService::addLikesAttributes($project);

        return response()->json($project);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProjectRequest $request
     * @return JsonResponse
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $author = User::find(auth()->user()->id);
        $project_count = Project::where('user_id', $author->id)->count();
        if($author->reputation < 50 && $project_count > 0){
            return response()->json(["message" => "Not Allowed", "errors" => [
                "reputation" => "You can create only one project with your reputation."
            ]], 403);
        }
        $project = Project::create([
            'title' => $request->title,
            'status' => Status::IDEE,
        ]);

        $version = Version::create([
            'number' => 1,
            'description' => $request->description
        ]);

        $version->project()->associate($project);
        $version->user()->associate($author);
        $version->save();

        $project->user()->associate($author);
        $project->save();

        ProjectService::createAndAttachTags($project, $request->tags);

        return response()->json(["project_id" => $project->id], 201);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function promote($id): JsonResponse
    {
        $project = Project::find($id);

        if($project == null){
            return response()->json(["message" => "Not Found", "errors" => [
                "id" => "Project does not exists"
            ]], 404);
        }

        if($project->status == Status::INITIATIVE){
            return response()->json(["message" => "Bad Request", "errors" => [
                "status" => "Project has already been promoted."
            ]], 400);
        }

        if($project->user->id != auth()->user()->id){
            return response()->json(["message" => "Not Allowed", "errors" => [
                "author" => "It is not allowed to promote a project from someone else."
            ]], 404);
        }

        $sum_upvote = 0;

        foreach($project->likes as $like){
            if($like->value == Vote::UPVOTE){
                $sum_upvote += 1;
            }
        }

        if($sum_upvote < 50){
            return response()->json(["message" => "Not Allowed", "errors" => [
                "votes" => "There is not enough votes to get promoted."
            ]], 403);
        }

        $project->status = Status::INITIATIVE;
        $project->save();

        return response()->json("Promoted");
    }

    public function like(int $id, LikeRequest $request): JsonResponse
    {
        $project = Project::find($id);
        if($project == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "id" => "Question does not exist"
            ]], 404);

        if(auth()->user()->reputation < 50){
            return response()->json(["message" => "Not Allowed", "errors" => [
                "reputation" => "The user cannot vote with less than or equals 50"
            ]], 403);
        }

        $like = Like::where("user_id", $project->user->id)
            ->where("likeable_id",$project->id)
            ->where("likeable_type", $project->getMorphClass())
            ->first();


        $modified = false;
        if($like == null){
            $like = Like::create([
                "value" => $request->value
            ]);
            $like->user()->associate(auth()->user());
            $project->likes()->save($like);
        }else{
            $like->value = $request->value;
            $modified = true;
        }
        $like->save();

        LikeService::addVoteReputation($project->user, $like->value, auth()->user()->id, Likeable::PROJECT, $modified);

        return response()->json("Liked");
    }
}
