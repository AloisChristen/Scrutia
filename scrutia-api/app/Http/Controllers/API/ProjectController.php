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
        $search = QueryBuilder::for(Project::class)
            ->allowedFilters([
                AllowedFilter::scope('startDate'),
                AllowedFilter::scope('endDate'),
                AllowedFilter::scope('title'),
                AllowedFilter::scope('tags'),
                AllowedFilter::scope('status'),
            ])
            ->with('tags:title')
            ->withCount('likes')
            ->orderByDesc('likes_count')
            ->paginate();


        return response()->json($search);
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
            // Info about versions
            "versions",

            // Info about questions
            "versions.questions",

            // Info about answers
            "versions.questions.answers",

            // Info about users
            "versions.questions.answers.user:id,username",
            "versions.questions.user:id,username",

            // Info about tags
            "tags:title",
        ])->find($id);

        if($project == null){
            return response()->json(["message" => "Not Found", "errors" => [
                "id" => "Project does not exist."
            ]], 404);
        }

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
