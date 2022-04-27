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

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Project::all();
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
        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description
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
