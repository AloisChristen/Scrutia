<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LikeRequest;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use App\Http\Service\LikeService;
use App\Models\Answer;
use App\Models\Like;
use App\Models\Likeable;
use App\Models\Question;
use Carbon\Carbon;
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
                "question_id" => "Question does not exist"
            ]], 404);

        $answers_count = Answer::where('user_id', auth()->user()->id)->whereDate('created_at', Carbon::today())->count();

        if(auth()->user()->reputation <= 0 && $answers_count >= 10){
            return response()->json(["message" => "Not Allowed", "errors" => [
                "reputation" => "The user already posted 10 answers today with less or equals than 0 reputation"
            ]], 403);
        }

        $answer = Answer::create([
            'title' =>  $request->title,
            'description' => $request->description,
        ]);

        $answer->user()->associate(auth()->user()->id);
        $answer->question()->associate($question);
        $answer->save();

        LikeService::addNewAnswerReputation($question->user);

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
                "id" => "Question id does not exist"
            ]], 404);
        }

        if(auth()->user()->id != $answer->user->id && auth()->user()->reputation < 200){
            return response()->json(["message" => "Not Allowed", "errors" => [
                "reputation" => "User is not the author and do not have more than 200 reputation"
            ]], 403);
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
                "reputation" => "User is not allowed to perform this action"
            ]], 403);


        if($answer == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "id" => "Answer does not exist"
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
                "id" => "Answer does not exist"
            ]], 404);

        if(auth()->user()->reputation <= 50){
            return response()->json(["message" => "Not Allowed", "errors" => [
                "reputation" => "The user cannot vote with less than or equals 50"
            ]], 403);
        }

        $like = Like::where("user_id", $answer->user->id)
            ->where("likeable_id",$answer->id)
            ->where("likeable_type", "App\Models\Answer")
            ->first();

        $modified = false;
        if($like == null){
            $like = Like::create([
                "value" => $request->value
            ]);
            $like->user()->associate(auth()->user());
            $answer->likes()->save($like);
        } else {
            $like->value = $request->value;
            $modified = true;
        }

        $like->save();

        LikeService::addVoteReputation($answer->user, $like->value, auth()->user()->id, Likeable::ANSWER, $modified);

        return response()->json("Liked");
    }
}
