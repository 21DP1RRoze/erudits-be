<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerAnswerResource extends JsonResource
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
            'answer' => new AnswerResource($this->answer),
            'question_id' => $this->question->id,
            'question_group_id' => $this->question->question_group_id,
            'questioned_at' => $this->questioned_at,
            'answered_at' => $this->answered_at,
        ];
    }
}
