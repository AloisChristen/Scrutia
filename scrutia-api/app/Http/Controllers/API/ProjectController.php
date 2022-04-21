<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Project::all();
    }

    public function getProject($id)
    {
        return Project::where('id', $id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project = new Project();
        $project->title = $request->title;
        $project->timestamp = $request->timestamp;
        $project->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);

        return $project;
        /*
            // QUESTION : order for calling with ?
            // question: need first ?
            */
    }

    /**
     * Updateª the specified resource in storage.
     *
     * @param UpdateProjectRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function update(UpdateProjectRequest $request, $id)
    {
        $project = Project::with('tag')->find($id);
        if (! $request->validated()) {
            throw ValidationException::withMessages('bad request format');
        }
        $project->title = $request->title;

        $project->save();
        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res=Project::where('id',$id)->delete();
        return response()->json($res);
    }
}
