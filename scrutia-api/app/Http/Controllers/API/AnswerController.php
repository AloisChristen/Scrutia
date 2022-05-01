<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use App\Models\Answer;
use Illuminate\Http\JsonResponse;

class AnswerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAnswerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAnswerRequest $request)
    {
        //
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
}
