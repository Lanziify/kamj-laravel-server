<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Check if the 'quiz' relationship is loaded
        $quiz = $this->whenLoaded('quiz');

        // Initialize quizId to null
        $quizId = null;

        // Ensure $quiz is not null and has the 'id' property
        if ($quiz && is_object($quiz) && isset($quiz->id)) {
            $quizId = $quiz->id;
        }

        return [
            'id' => $this->id,
            'code' => $this->code,
            'expires_at' => $this->expires_at,
            'quiz' => $quizId,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
