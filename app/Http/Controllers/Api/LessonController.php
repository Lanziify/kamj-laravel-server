<?php

namespace App\Http\Controllers\Api;

use App\Models\Lesson;
use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LessonController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:api', except: ['index', 'show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lessons = Lesson::with('topics')->orderBy('title')->get();
        return response()->json([
            "lessons" => LessonResource::collection($lessons)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields =  $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Lesson::create($fields);

        return [
            'message' => 'Lesson created successfully'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson, Request $request)
    {
        $topicId = $request->query('topic');

        $lesson = $lesson->load([
            'topics' => function ($query) use ($topicId) {
                if ($topicId) $query->where('id', $topicId);
            }
        ]);

        return LessonResource::make($lesson);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        $lesson->update($request->all());
        return LessonResource::make($lesson);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return [
            'message' => 'The lesson has been deleted'
        ];
    }
}
