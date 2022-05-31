<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Service\ProjectService;
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
        $projects = QueryBuilder::for(Project::class)
            ->allowedFilters([
                AllowedFilter::scope('startDate'),
                AllowedFilter::scope('endDate'),
                AllowedFilter::scope('title'),
                AllowedFilter::scope('tags'),
                AllowedFilter::scope('content'),
                AllowedFilter::scope('status'),
            ])
            ->with(["versions.questions" => function($query){
                $query->limit(3);
                $query->with(["answers" => function($query){
                    $query->limit(3);
                }]);
            }])
            ->with("tags")
            ->get();

        foreach ($projects as $project){
            ProjectService::addLikesAttributes($project);
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
        $project = Project::with("versions.questions.answers")
            ->with("tags")
            ->find($id);

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

        return response()->json("Created", 201);
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
        $version = $project->versions()->with('likes')->first();

        foreach($version->likes as $like){
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

    public function displayByTags($tag): JsonResponse
    {
        $projects = Project::with('tag')->whereHas('tag', function ($query) use ($tag) {
            $query->where('name', $tag);
        })->get();

        return response()->json($projects);
    }


    public function displayBetweenDates($start, $end): JsonResponse
    {
        return response()->json(
                (new Project)
                ->whereBetween('created_at', $start, $end)
                ->get()
        );
    }

    public function displayByTagsAndDates($tag, $start, $end): JsonResponse
    {
        return response()->json(
                (new Project)
                ->whereBetween('created_at', $start, $end)
                ->whereHas('tag', function ($query) use ($tag) {
                    $query->where('name', $tag);
                })
                ->get()
        );
    }
}
