<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizInstanceRequest extends FormRequest
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
        return [
            'title' => 'required|max:64',
            'description' => 'nullable|max:512',
            'is_public' => 'boolean|required',
            'is_active' => 'boolean|required',
            'quiz_id' => 'required',
        ];
    }
}
