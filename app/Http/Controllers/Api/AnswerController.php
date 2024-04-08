<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnswerRequest;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use App\Models\OpenAnswer;
use App\Models\PlayerAnswer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AnswerResource::collection(Answer::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnswerRequest $request)
    {
        $validated = $request->validated();
        return new AnswerResource(Answer::create($validated));
    }

    /**
     * Display the specified resource.
     */
    public function show(Answer $answer)
    {
        return new AnswerResource($answer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AnswerRequest $request, Answer $answer)
    {
        $validated = $request->validated();
        $answer->update($validated);
        return new AnswerResource($answer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Answer $answer)
    {
        $answer->delete();
        return response()->json();
    }

    public function setSelectedAnswer(Request $request)
    {
        $validated = $request->validate([
            'player_id' => 'required|integer',
            'question_id' => 'required|integer',
            'answer_id' => 'required|integer',
        ]);

        $updatedPlayerAnswer = null;
        $playerAnswer = PlayerAnswer::where('player_id', $validated['player_id'])
            ->where('question_id', $validated['question_id'])
            ->first();
        if ($playerAnswer) {
            $updatedPlayerAnswer = $playerAnswer->update(['answer_id' => $validated['answer_id']]);
        } else {
            $updatedPlayerAnswer = PlayerAnswer::create($validated);
        }
        return response()->json();
    }

    public function setOpenAnswer(Request $request)
    {
        $validated = $request->validate([
            'player_id' => 'required|integer',
            'question_id' => 'required|integer',
            'answer' => 'required|string',
        ]);

        $openAnswer = OpenAnswer::where('player_id', $validated['player_id'])
            ->where('question_id', $validated['question_id'])
            ->first();
        if ($openAnswer) {
            $openAnswer->update(['answer' => $validated['answer']]);
        } else {
            OpenAnswer::create($validated);
        }
        return response()->json();
    }

    public function setTiebreakerAnswer(Request $request)
    {
        $validated = $request->validate([
            'player_id' => 'required|integer',
            'question_id' => 'required|integer',
            'answer_id' => 'required|integer',
            'questioned_at' => 'required|date',
            'answered_at' => 'required|date',
        ]);

        $playerAnswer = PlayerAnswer::where('player_id', $validated['player_id'])
            ->where('question_id', $validated['question_id'])
            ->first();

        if ($playerAnswer) {
            $playerAnswer->update($validated);
        } else {
            PlayerAnswer::create($validated);
        }

        return response()->json();
    }
}
