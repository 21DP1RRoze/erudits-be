<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionGroupResource extends JsonResource
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
            'title' => $this->title,
            'disqualify_amount' => $this->disqualify_amount,
            'answer_time' => $this->answer_time,
            'points' => $this->points,
            'is_additional' => $this->is_additional,
            'questions' => QuestionResource::collection($this->questions),
        ];
    }
}
