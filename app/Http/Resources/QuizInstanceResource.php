<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizInstanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'is_public'=>$this->is_public,
            'is_active'=>$this->is_active,
            'active_question_group'=> new QuestionGroupResource($this->activeQuestionGroup),
            'quiz' => new QuizResource($this->quiz),
        ];
    }
}
