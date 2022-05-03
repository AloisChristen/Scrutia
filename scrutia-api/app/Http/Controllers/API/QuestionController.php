<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyQuestionRequest;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Service\UserService;
use App\Models\Question;
use App\Models\User;
use App\Models\Version;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use phpDocumentor\Reflection\Project;

class QuestionController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreQuestionRequest $request
     * @return JsonResponse
     */
    public function store(StoreQuestionRequest $request): JsonResponse
    {
        $project_version = Version::where('project_id', $request->project_id)->where('number', $request->version_number)->first();
        if($project_version == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "Project id or version number does not exist"
            ]], 404);

        $question = Question::create([
            'title' =>  $request->title,
            'description' => $request->description,
        ]);

        $question->user()->associate(auth()->user()->id);
        $question->version()->associate($project_version);
        $question->save();
        UserService::addQuestionReputation($question->user);

        return response()->json("Created", 201);
    }
   /**
     * Update the specified resource in storage.
     *
     * @param UpdateQuestionRequest $request
     * @param int $question
     * @return JsonResponse
     */
    public function update(int $id, UpdateQuestionRequest $request): JsonResponse
    {
        $question = Question::find($id);
        if($question == null){
            return response()->json(["message" => "Not Found", "errors" => [
                "Question id does not exist"
            ]], 404);
        }
        $question->title = $request->title;
        $question->description = $request->description;
        $question->save();
        return response()->json($question);
    }

   /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $question = Question::find($id);
        if(auth()->user()->id != $question->user->id && auth()->user()->reputation < 300)
                return response()->json(["message" => "Not Allowed", "errors" => [
                    "User is not allowed to perform this action"
                ]], 403);


        if($question == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "Question id does not exist"
            ]], 404);

        $question->answers()->delete();
        $question->likes()->delete();
        $question->delete();
        return response()->json("Deleted");
    }
}
