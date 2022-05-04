<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LikeRequest;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use App\Http\Service\UserService;
use App\Models\Answer;
use App\Models\Like;
use App\Models\Question;
use Illuminate\Http\JsonResponse;

class AnswerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAnswerRequest $request
     * @return JsonResponse
     */
    public function store(StoreAnswerRequest $request): JsonResponse
    {
        $question = Question::find($request->question_id);
        if($question == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "Question does not exist"
            ]], 404);

        $answer = Answer::create([
            'title' =>  $request->title,
            'description' => $request->description,
        ]);

        $answer->user()->associate(auth()->user()->id);
        $answer->question()->associate($question);
        $answer->save();

        UserService::addAnswerReputation($answer->user);

        return response()->json("Created", 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param UpdateAnswerRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UpdateAnswerRequest $request): JsonResponse
    {
        $answer = Answer::find($id);
        if($answer == null){
            return response()->json(["message" => "Not Found", "errors" => [
                "Question id does not exist"
            ]], 404);
        }
        $answer->title = $request->title;
        $answer->description = $request->description;
        $answer->save();
        return response()->json($answer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $answer= Answer::find($id);
        if(auth()->user()->id != $answer->user->id && auth()->user()->reputation < 200)
            return response()->json(["message" => "Not Allowed", "errors" => [
                "User is not allowed to perform this action"
            ]], 403);


        if($answer == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "Answer does not exist"
            ]], 404);

        $answer->likes()->delete();
        $answer->delete();
        return response()->json("Deleted");
    }

    public function like(int $id, LikeRequest $request): JsonResponse
    {
        $answer = Answer::find($id);
        if($answer == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "Answer does not exist"
            ]], 404);

        $like = Like::where("user_id", $answer->user->id)
            ->where("likeable_id",$answer->id)
            ->where("likeable_type", "App\Models\Answer")
            ->firstOr(function() use($answer, $request) {
                $like = Like::create([
                    "value" => $request->value
                ]);
                $like->user()->associate(auth()->user());
                $answer->likes()->save($like);
                return $like;
            });

        $like->value = $request->value;
        $like->save();

        return response()->json("Liked");
    }
}
