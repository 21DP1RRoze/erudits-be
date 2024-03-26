<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return QuestionResource::collection(Question::all());

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionRequest $request)
    {
        $validated = $request->validated();
        // I have no idea why, but the backend always returns is_open_answer as null
        // Even though the database clearly makes the default value as false, it still doesn't work
        // That's why I'm specifying it here
        $validated['is_open_answer'] = false;
        $question = Question::create($validated);

        for ($i = 1; $i <= 4; $i++) {
            $question->answers()->create([
                'text' => 'Answer ' . $i,
                'is_correct' => $i % 4 == 0, // Sets the last answer as the correct one by default
            ]);
        }

        return new QuestionResource($question);
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return new QuestionResource($question);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $validated = $request->validated();
        $question->update($validated);
        return new QuestionResource($question);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json();
    }
}
