<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Service\ProjectService;
use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use App\Models\Version;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    /**
     * Display projects based on filters.
     * @return Collection|QueryBuilder[]
     */
    public function index(): Collection|array
    {
        return QueryBuilder::for(Project::class)
                ->allowedFilters([
                    AllowedFilter::scope('startDate'),
                    AllowedFilter::scope('endDate'),
                    AllowedFilter::scope('title'),
                    AllowedFilter::scope('tags'),
                    AllowedFilter::scope('content'),
                    AllowedFilter::scope('status'),
                ])
                ->get();
    }

    /**
     * Return the specific resource
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $result = Project::with('versions', 'tags')->find($id);

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

        $v0 = Version::create([
            'number' => 1,
            'description' => $request->description,
            'author' => $author->id,
        ]);
        $v0->project()->associate($project);
        $v0->save();

        $project->user()->associate($author->id);
        auth()->user()->projects()->save($project);

        ProjectService::createAndAttachTags($project, $request->tags);

        return response()->json($project);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function promote($id): JsonResponse
    {
        $projectToPromote = Project::where('id', $id);
        dd($projectToPromote);
        $ideaVersion = Version::where('project_id', $id);


        $v2 = Version::create([
            'number' => 2,
            'author' => $ideaVersion->author,
            'status' => Status::INITIATIVE,
            'description' => $ideaVersion->description,
        ]);
        $v2->project()->associate($project);
        $v2->save();
        $project->versions->save($v2);

        return $project;
    }
}
