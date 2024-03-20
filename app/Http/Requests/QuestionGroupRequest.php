<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
//        return [
//            'title' => 'required',
//            'disqualify_amount' => 'required|integer',
//            'answer_time' => 'required',
//            'points' => 'required|integer',
//            'is_additional' => 'required|boolean',
//            'quiz_id' => 'required|exists:quizzes,id',
//        ];
        return [
            'title' => '',
            'disqualify_amount' => 'integer',
            'answer_time' => '',
            'points' => 'integer',
            'is_additional' => 'boolean',
            'quiz_id' => 'required|exists:quizzes,id',
        ];
    }
}
