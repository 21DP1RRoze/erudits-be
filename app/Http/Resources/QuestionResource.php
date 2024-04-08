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
            'text' => $this->text,
            'image' => $this->image,
            'is_open_answer' => $this->is_open_answer,
            'guidelines' => $this->guidelines,
            'correct_answer' => $this->correct_answer,
            'answers' => AnswerResource::collection($this->answers),
        ];
    }
}
