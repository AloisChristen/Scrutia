<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Service\ProjectService;
use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use App\Models\Version;
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
            ->paginate();

        return response()->json($projects);
    }

    /**
     * Return the specific resource
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $result = Project::with("versions.questions.answers")
            ->with("tags")
            ->find($id);

        return response()->json($result);
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

        if($project->user->id != auth()->id){
            return response()->json(["message" => "Not Allowed", "errors" => [
                "author" => "It is not allowed to promote a project from someone else."
            ]], 404);
        }

        $project->status = Status::INITIATIVE;
        $project->save();

        return response()->json("Promoted");
    }
}
