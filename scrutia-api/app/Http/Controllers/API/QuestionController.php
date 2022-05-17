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
                "Project id or version number does not exist"
            ]], 404);

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
     * @param int $id
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

        $question->likes()->delete();
        $question->delete();
        return response()->json("Deleted");
    }

    public function like(int $id, LikeRequest $request): JsonResponse
    {
        $question = Question::find($id);
        if($question == null)
            return response()->json(["message" => "Not Found", "errors" => [
                "Question does not exist"
            ]], 404);

        $like = Like::where("user_id", $question->user->id)
            ->where("likeable_id",$question->id)
            ->where("likeable_type", "App\Models\Question")
            ->firstOr(function() use($question, $request) {
                $like = Like::create([
                    "value" => $request->value
                ]);
                $like->user()->associate(auth()->user());
                $question->likes()->save($like);
                return $like;
            });

        $like->value = $request->value;
        $like->save();

        /**
         * TODO Discuss if we let this "bug" with the group
         * Check if it's the first like or not, if it's not, we have to remove old reputation and add the new (positiv or negativ)
         * Otherwise, you can put upvote, then downvote and then upvote again and you can gain reputation infinitely
         * As upvoting gain more reputation than downvoting remove reputation
         * (addAnswerVoteReputation implementing a third parameter for that with a default value to false meaning that he's not been modified)
         **/
        LikeService::addVoteReputation($question->user, $like->value, auth()->id, Likeable::QUESTION);

        return response()->json("Liked");
    }
}
