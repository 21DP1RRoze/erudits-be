<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // If user is not logged in, don't show the correct answer
        if (!auth()->check()) {
            return [
                'id' => $this->id,
                'text' => $this->text,
            ];
        }
        // If user is logged in, show the correct answer
        return [
            'id' => $this->id,
            'text' => $this->text,
            'is_correct' => $this->is_correct,
        ];
    }
}
