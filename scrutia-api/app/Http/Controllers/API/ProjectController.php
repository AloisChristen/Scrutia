<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Service\ProjectService;
use App\Models\Project;
use App\Models\Status;
use App\Models\Version;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\Paginator;

class ProjectController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Project::paginate();
    }

    /**
     * Display a listing of the resource (only ideas).
     *
     * @return Response
     */
    public function showIdeas() {
        // TODO: project qui sont encore des idées:
        // "select project_id, count(*) c from versions group by project_id having c=1" -> comment faire en laravel
        return Project::paginate();
    }

    public function showInitiatives() {
        // TODO: showInitiatives
        // "select project_id, count(*) c from versions group by project_id having c=1"
        // enlever ces projet de tout les projets
        return Project::paginate();
    }

    public function promote($id) {
        $project = Project::where('id', $id);
        $ideaVersion = Version::where('project_id', $project['id']);

        $v2 = Version::create([
            'number' => 2,
            'author' => $ideaVersion['author'],
            'status' => Status::INITIATIVE,
            'description' => $ideaVersion['description']
        ]);
        $v2->project()->associate($project);
        $v2->save();
        $project->versions->save($v2);

        return $project;
    }

    /**
     * Return the specific resource
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $result = Project::with('versions', 'tags')->find($id);
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $author = auth()->user();

        $project = Project::create([
            'title' => $request->title,
        ]);

        $v0 = Version::create([
            'number' => 1,
            'status' => Status::IDEE,
            'description' => $request->description,
            'author' => $author->id
        ]);
        $v0->project()->associate($project);
        $v0->save();


        $project->user()->associate($author->id);
        auth()->user()->projects()->save($project);

        ProjectService::createAndAttachTags($project, $request->tags);

        return response()->json($project);
    }

    /**
     * Updateª the specified resource in storage.
     *
     * @param UpdateProjectRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateProjectRequest $request, int $id): JsonResponse
    {
        $project = Project::with('tags')->find($id);

        $project->title = $request->title;
        $project->description = $request->description;
        $project->save();
        ProjectService::createAndAttachTags($project, $request->tags);

        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $project = Project::find($id);
        $project->tags()->detach();
        $project->delete();
        return response()->json($project);
    }
}
