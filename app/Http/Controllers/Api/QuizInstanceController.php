<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PlayerResource;
use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuizInstanceRequest;
use App\Http\Resources\QuizInstanceResource;
use App\Models\QuizInstance;

class QuizInstanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return QuizInstanceResource::collection(QuizInstance::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuizInstanceRequest $request)
    {
        $validated = $request->validated();
        return new QuizInstanceResource(QuizInstance::create($validated));
    }

    /**
     * Display the specified resource.
     */
    public function show(QuizInstance $quizInstance)
    {
        return new QuizInstanceResource($quizInstance);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuizInstanceRequest $request, QuizInstance $quizInstance)
    {
        $validated = $request->validated();

        $quizInstance->update($validated);
        return new QuizInstanceResource($quizInstance);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuizInstance $quizInstance)
    {
        $quizInstance->delete();
        return response()->json();
    }


    public function getPublicQuizzes()
    {
        $quizzes = QuizInstance::where('is_public', true)->get();
        return QuizInstanceResource::collection($quizzes);
    }

    public function getActiveQuizzes()
    {
        $quizzes = QuizInstance::where('is_active', true)->get();
        return QuizInstanceResource::collection($quizzes);
    }

    public function getActivePublicQuizzes()
    {
        $quizzes = QuizInstance::where('is_active', true)
            ->where('is_public', true)
            ->get();
        return QuizInstanceResource::collection($quizzes);
    }

    public function getQuizInstancePlayers(QuizInstance $quizInstance)
    {
        return PlayerResource::collection($quizInstance->players()->get());
    }

    // Method that will be used by pollers to check if a new question group is active
    public function getActiveQuestionGroup(QuizInstance $quizInstance)
    {
        return $quizInstance->activeQuestionGroup()->get();
    }

    public function setActiveQuestionGroup(Request $request, QuizInstance $quizInstance)
    {
        $validated = $request->validate([
            'question_group_id' => 'required|integer',
        ]);

        $quizInstance->update(['active_question_group_id' => $validated['question_group_id']]);
        return response()->json();
    }

    public function setQuizInstanceActive(QuizInstance $quizInstance)
    {
        $quizInstance->update(['is_active' => true]);
        return response()->json();
    }

    public function setAllPlayersActive(QuizInstance $quizInstance)
    {
        foreach ($quizInstance->players() as $player) {
            $player->is_active = true;
            $player->save();
        }
        return response()->json();
    }

    public function setAllPlayersInactive(QuizInstance $quizInstance)
    {
        foreach ($quizInstance->players() as $player) {
            $player->is_active = false;
            $player->save();
        }
        return response()->json();
    }

    public function handleQuestionGroupPoll(QuizInstance $quizInstance)
    {
        if ($this->hasQuestionGroupEnded($quizInstance)) {
            $this->setAllPlayersInactive($quizInstance);
            $quizInstance->active_question_group_id = null;
            $quizInstance->active_question_group_start = null;

            $quizInstance->save();
        }
    }

    public function hasQuestionGroupEnded(QuizInstance $quizInstance)
    {
        if ($quizInstance->active_question_group_id != null) {
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

            foreach ($players as $player) {
                if ($player->is_active) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}
