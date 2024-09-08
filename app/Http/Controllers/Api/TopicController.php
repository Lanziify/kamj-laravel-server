<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TopicResource;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TopicController extends Controller implements HasMiddleware
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
        return response()->json([
            "topics" => TopicResource::collection(Topic::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields =  $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'description' => '',
            'content' => 'required|string',
        ]);

        Topic::create($fields);

        return [
            "message" => "Topic created successfully"
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic)
    {
        return $topic;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();
        return [
            'message' => 'Topic deleted successfully'
        ];
    }
}
