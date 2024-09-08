<?php

namespace App\Http\Resources;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $topic = Topic::findOrFail($this->topic_id);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'active' => $this->active,
            'time_limit' => $this->time_limit,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'topic' =>  [
                'id' => $topic->id,
                'title' => $topic->title,
            ],
            'items' =>  QuestionResource::collection($this->whenLoaded('questions')),
            'codes' =>  QuizCodeResource::collection($this->whenLoaded('codes')),
        ];
    }
}
