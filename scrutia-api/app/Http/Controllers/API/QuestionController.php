<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Question::all();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

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

        return response()->json($question, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Question $id
     * @return JsonResponse
     */
    public function show(Question $id): JsonResponse
    {
        $question = Question::find($id);

        return response()->json($question);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @return Response
     */
    public function edit(Question $question): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateQuestionRequest $request
     * @param Question $question
     * @return JsonResponse
     */
    public function update(UpdateQuestionRequest $request, Question $question): JsonResponse
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
    public function destroy($id): JsonResponse
    {
        $res=Question::where('id',$id)->delete();
        return response()->json($res);
    }
}
