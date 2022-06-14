<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFavoriteRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = User::find(auth()->user()->id);

        if($user == null){
            return response()->json(["message" => "Not Found", "errors" => [
                "User does not exist"
            ]], 404);
        }

        return response()->json($user->favorites()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFavoriteRequest $request
     * @return JsonResponse
     */
    public function store(StoreFavoriteRequest $request)
    {
        $user = User::find(auth()->user()->id);

        if($user == null){
            return response()->json(["message" => "Not Found", "errors" => [
                "User does not exist"
            ]], 404);
        }

        $project = Project::find($request->project_id);

        if($project == null){
            return response()->json(["message" => "Not Found", "errors" => [
                "Project does not exist"
            ]], 404);
        }

        // Check that if the user already added the project into his favorites, we do not add it again
        $favorite = $user->favorites()->where("project_id",$project->id)->first();

        if($favorite == null)
            $user->favorites()->save($project);

        return response()->json("Created", 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::find(auth()->user()->id);

        if($user == null){
            return response()->json(["message" => "Not Found", "errors" => [
                "User does not exist"
            ]], 404);
        }

        $project = Project::find($id);

        if($project == null){
            return response()->json(["message" => "Not Found", "errors" => [
                "Project does not exist"
            ]], 404);
        }

        $user->favorites()->detach($project->id);


        return response()->json("Deleted");
    }
}
