<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizCodeResource;
use App\Models\Quiz;
use App\Models\QuizCode;
use Illuminate\Http\Request;

class QuizCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $codes = QuizCode::with('quiz')->get();

        return response()->json([
            "codes" => QuizCodeResource::collection($codes)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $quiz = Quiz::findOrFail($request->quiz_id);

        $fields = $request->validate([
            'expires_at' => 'nullable|date'
        ]);

        $quizCode = new QuizCode($fields);

        $quizCode = $quiz->codes()->save($quizCode);

        return response()->json(new QuizCodeResource($quizCode), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($quizCode)
    {
        return QuizCode::findOrFail($quizCode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuizCode $quizCode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $code = QuizCode::findOrFail($id);
        $code->delete();

        return response()->json([
            'title' => 'Code Deleted',
            'message' => 'Quiz code successfully deleted'
        ]);
    }
}
