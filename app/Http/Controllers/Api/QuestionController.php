<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($quiz_id, Request $request)
    {
        $quiz = Quiz::findOrFail($quiz_id);

        $question_fields = $request->validate([
            // 'question_type_id' => 'required|exists:question_types,id',
            'question' => 'required|string',
            'answer' => 'required|integer'
        ]);

        $question = new Question($question_fields);

        $question = $quiz->questions()->save($question);

        $saved_question = Question::findOrFail($question->id);

        // switch ($request->question_type_id) {
        //     case 1:
        foreach ($request->choices as $choiceData) {
            $choice = new QuestionChoice([
                "label" => $choiceData
            ]);
            $saved_question->choices()->save($choice);
        }
        // }

        return [
            'message' => 'Question added successfully'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return $question;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response()->json([
            'title' => 'Question deleted',
            'message' => 'Question deleted successfully',
        ]);
    }
}
