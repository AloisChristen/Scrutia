<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Service\ProjectService;
use App\Models\Project;
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
        // donc version qui ont status=idee et dont il n'existe aucune version avec le meme project_ide qui ont status=initiative
        // + mettre data venant du project avec le project_id semblable
        // -> pareil pour showInitiatives
    }

    public function showInitiatives() {

    }

    public function promote($id) {
        $project = Project::where('id', $id);
        // TODO: how to do there ?
        // idea: duplicate last version and have "initiative" instead of "idee" in the status field
        // to confirm
    }

    public function getProject($id)
    {

        return Project::where('id', $id);
    }

    /**
     * Return the specific resource
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        //TODO Check DTO on swagger, it misses some informations
        return response()->json(Project::find($id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $user_id = 11; // TODO: get user id from token or something

        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $user_id, // QUESTION: why not taken in account ? -> create sql exception
        ]);

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
