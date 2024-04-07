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
            'active_question_group_start' => $this->active_question_group_start,
            'has_question_group_ended' => $this->hasQuestionGroupEnded($this),
        ];
    }

    protected function hasQuestionGroupEnded($quizInstance)
    {
        if ($quizInstance->active_question_group_id != null) {
            if ($quizInstance->active_question_group_start == null) {
                return false;
            }
            // Check if the time has run out
            $endTime = $quizInstance->active_question_group_start->addMinutes($quizInstance->activeQuestionGroup->answer_time);
            if (now()->greaterThan($endTime)) {
                return true;
            }

            // Check if all active players have finished the question group
            $players = $quizInstance->players()->where('is_disqualified', false);
            if ($players->count() == 0) {
                return true;
            }

            foreach ($players->get() as $player) {
                if ($player->is_active) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}
