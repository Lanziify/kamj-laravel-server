<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class QuizController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:api', except: ['index', 'show', 'total'])
        ];
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $offset = ($page - 1) * $limit;

        $active = $request->query('active');

        $query = Quiz::with('questions.choices', 'codes')
            ->orderByDesc('created_at')
            ->skip($offset)
            ->take($limit);

        if ($active) {
            $query->where('active',  $active);
        }

        $quizzes = $query->get();

        return [
            'quizzes' => QuizResource::collection($quizzes),
            'total' => Quiz::count(),
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'title' => 'required|string',
            'description' => '',
        ]);

        Quiz::create($fields);

        return [
            'title' => 'Quiz Created',
            'message' => 'Quiz created successfully'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz, Request $request)
    {
        $random  = (int) $request->query('random', 0);

        $quiz->load([
            'questions' => function ($query) use ($random) {
                if ($random) {
                    $query->inRandomOrder();
                }
                $query->with('choices');
            }
        ])->load('codes');

        return QuizResource::make($quiz);
    }

    public function codes($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        $quiz->load('codes');

        return response()->json(QuizResource::make($quiz));
        // return QuizCode::findOrFail($quizCode);
    }

    public function items($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        $quiz->load('questions.choices');

        return response()->json(QuizResource::make($quiz));
        // return QuizCode::findOrFail($quizCode);
    }

    public function total()
    {
        $total = Quiz::count(); // Count the number of quizzes
        return response()->json(['total' => $total]);

        // return ['total' => Quiz::count()];
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $quiz->update($request->all());
        return QuizResource::make($quiz);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return [
            "title" => "Delete Quiz",
            "message" => "Quiz deleted successfully"
        ];
    }
}
