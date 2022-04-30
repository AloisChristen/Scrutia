<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyQuestionRequest;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
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
        $question = Question::create([
            'title' =>  $request->title,
            'description' => $request->description,
        ]);
        $project_version = Version::where('project_id', $request->project_id)->where('number', $request->version_number)->first();

        //$question->author()->associate(auth()->user()->id); TODO : uncomment when there's the middleware in place
        $user = User::find($request->user_id);
        $question->user()->associate($user);
        $question->version()->associate($project_version);
        $question->save();

        return response()->json($question, 201);
    }
   /**
     * Update the specified resource in storage.
     *
     * @param UpdateQuestionRequest $request
     * @param Question $question
     * @return JsonResponse
     */
    public function update(Question $question, UpdateQuestionRequest $request): JsonResponse
    {
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
    public function destroy(Question $question): JsonResponse
    {
        $question->answers()->delete();
        $question->delete();
        return response()->json("Deleted");
    }
}
