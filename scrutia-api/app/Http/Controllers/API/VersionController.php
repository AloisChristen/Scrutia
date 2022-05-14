<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LikeRequest;
use App\Http\Requests\StoreVersionRequest;
use App\Http\Requests\UpdateVersionRequest;
use App\Models\Like;
use App\Models\Project;
use App\Models\Status;
use App\Models\Version;
use Illuminate\Http\JsonResponse;

class VersionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVersionRequest $request
     * @return JsonResponse
     */
    public function store(StoreVersionRequest $request): JsonResponse
    {
        $project = Project::find($request->project_id);
        if($project == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "Project does not exist"
            ]], 404);
        if($project->user->id != auth()->user()->id)
            return response()->json(["message" => "Not Allowed", "errors" => [
                "User is not allowed to perform this action"
            ]], 403);

        $version = Version::create([
            "number" => Version::where("project_id", $project->id)->count() + 1,
            "description" => $request->description,
            "status" => Status::INITIATIVE
        ]);

        $version->project()->associate($project);
        $version->user()->associate(auth()->user());
        $version->save();

        return response()->json("Created", 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param UpdateVersionRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UpdateVersionRequest $request): JsonResponse
    {
        $version = Version::find($id);
        if($version->user->id != auth()->user()->id)
            return response()->json(["message" => "Not Allowed", "errors" => [
                "User is not allowed to perform this action"
            ]], 403);

        if($version == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "Version does not exists"
            ]], 404);

        $version->description = $request->description;
        $version->save();

        return response()->json("Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $version = Version::find($id);
        if(auth()->user()->id != $version->user->id)
            return response()->json(["message" => "Not Allowed", "errors" => [
                "User is not allowed to perform this action"
            ]], 403);


        if($version == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "Version does not exist"
            ]], 404);


        $version->likes()->delete();
        $version->delete();

        return response()->json("Deleted");
    }

    /**
     * Like the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function like(int $id, LikeRequest $request): JsonResponse
    {
        $version = Version::find($id);
        if($version == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "Version does not exist"
            ]], 404);

        $like = Like::where("user_id", $version->user->id)
            ->where("likeable_id",$version->id)
            ->where("likeable_type", "App\Models\Version")
            ->firstOr(function() use($version, $request) {
                $like = Like::create([
                    "value" => $request->value
                ]);
                $like->user()->associate(auth()->user());
                $version->likes()->save($like);
                return $like;
            });

        $like->value = $request->value;
        $like->save();

        return response()->json("Liked");
    }
}
