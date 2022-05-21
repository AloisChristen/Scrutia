<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyQuestionRequest;
use App\Http\Requests\LikeRequest;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Service\LikeService;
use App\Models\Like;
use App\Models\Likeable;
use App\Models\Question;
use App\Models\Version;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

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
                "project_id" => "Project id or version number does not exist"
            ]], 404);


        $questions_count = Question::where('user_id', auth()->id)->whereDate('created_at', Carbon::today())->count();
        if(auth()->user()->reputation <= 0 && $questions_count >= 1){
            return response()->json(["message" => "Not Allowed", "errors" => [
                "reputation" => "The user already posted today with less or equals than 0 reputation"
            ]], 403);
        }
        elseif(auth()->user()->reputation < 100 && $questions_count >= 10){
            return response()->json(["message" => "Not Allowed", "errors" => [
                "reputation" => "The user already posted 10 questions today with less or equals than 100 reputation"
            ]], 403);
        }


        $question = Question::create([
            'title' =>  $request->title,
            'description' => $request->description,
        ]);

        $question->user()->associate(auth()->user()->id);
        $question->version()->associate($project_version);
        $question->save();

        LikeService::addNewQuestionReputation($project_version->user);

        return response()->json("Created", 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param UpdateQuestionRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UpdateQuestionRequest $request): JsonResponse
    {
        $question = Question::find($id);
        if($question == null){
            return response()->json(["message" => "Not Found", "errors" => [
                "id" => "Question id does not exist"
            ]], 404);
        }

        if(auth()->user()->id != $question->user->id && auth()->user()->reputation < 200){
            return response()->json(["message" => "Not Allowed", "errors" => [
                "reputation" => "User is not the author and do not have more than 200 reputation"
            ]], 403);
        }

        $question->title = $request->title;
        $question->description = $request->description;
        $question->save();
        return response()->json($question);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $question = Question::find($id);
        if(auth()->user()->id != $question->user->id && auth()->user()->reputation < 300)
                return response()->json(["message" => "Not Allowed", "errors" => [
                    "reputation" => "User is not allowed to perform this action"
                ]], 403);


        if($question == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "id" => "Question id does not exist"
            ]], 404);

        $question->likes()->delete();
        $question->delete();
        return response()->json("Deleted");
    }

    public function like(int $id, LikeRequest $request): JsonResponse
    {
        $question = Question::find($id);
        if($question == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "id" => "Question does not exist"
            ]], 404);

        if(auth()->user()->reputation <= 50){
            return response()->json(["message" => "Not Allowed", "errors" => [
                "reputation" => "The user cannot vote with less than or equals 50"
            ]], 403);
        }

        $like = Like::where("user_id", $question->user->id)
            ->where("likeable_id",$question->id)
            ->where("likeable_type", "App\Models\Question")
            ->first();


        $modified = false;
        if($like == null){
            $like = Like::create([
                "value" => $request->value
            ]);
            $like->user()->associate(auth()->user());
            $question->likes()->save($like);
        }else{
            $like->value = $request->value;
            $modified = true;
        }
        $like->save();
        
        LikeService::addVoteReputation($question->user, $like->value, auth()->id, Likeable::QUESTION, $modified);

        return response()->json("Liked");
    }
}
