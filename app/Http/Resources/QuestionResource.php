<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            // 'question_type_id' => $this->question_type_id,
            'question' => $this->question,
            'answer' => $this->answer,
            'choices' => QuestionChoiceResource::collection($this->whenLoaded('choices'))
        ];
    }
}
