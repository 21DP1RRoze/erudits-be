<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PlayerResource;
use App\Http\Resources\QuestionResource;
use App\Models\Player;
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
        $this->calculatePoints($quizInstance);

        return PlayerResource::collection($quizInstance->players()->get());
    }

    public function calculatePoints(QuizInstance $quizInstance)
    {
        $players = $quizInstance->players()->get();

        foreach ($players as $player) {
            $player->points = 0;
            $player->tiebreaker_points = 0;

            foreach ($player->player_answers as $playerAnswer) {
                $questionGroup = $playerAnswer->question->question_group;
                $pointsToAdd = $questionGroup->points;

                if ($questionGroup->is_additional) {
                    if ($playerAnswer->answer->is_correct) {
                        $player->tiebreaker_points += $pointsToAdd;
                    }
                } else {
                    if ($playerAnswer->answer->is_correct) {
                        $player->points += $pointsToAdd;
                    }
                }
            }

            foreach ($player->open_answers as $openAnswer) {
                $pointsToAdd = $openAnswer->points;
                $player->points += $pointsToAdd;
            }

            $player->save();
        }
    }

    // Method that will be used by pollers to check if a new question group is active
    public function getActiveQuestionGroup(QuizInstance $quizInstance)
    {
        return $quizInstance->activeQuestionGroup()->get();
    }

    public function setActiveQuestionGroup(Request $request, QuizInstance $quizInstance)
    {
        $validated = $request->validate([
            'question_group_id' => 'nullable|integer',
            'question_group_time' => 'nullable',
        ]);
        if(isset($validated['question_group_id'])) {
            $quizInstance->update([
                'active_question_group_id' => $validated['question_group_id'],
                'active_question_group_start' => $validated['question_group_time'],
            ]);

        } else {
            $quizInstance->update([
                'active_question_group_id' => null,
                'active_question_group_start' => null,
            ]);
        }
        return response()->json([
            'data' => new QuizInstanceResource($quizInstance),
        ]);
    }

    public function setActiveTiebreakerQuestionGroup(QuizInstance $quizInstance)
    {
        $questionGroup = $quizInstance->quiz->questionGroups()->where('is_additional', true)->first();
        $quizInstance->update([
            'active_question_group_id' => $questionGroup->id,
            'active_question_group_start' => now(),
        ]);
        return response()->json([
            'data' => new QuizInstanceResource($quizInstance),
        ]);
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

    public function hasActiveQuestionGroupPoll(QuizInstance $quizInstance, Player $player)
    {
        if($quizInstance->active_question_group_id == null) {
            return response()->json([
                'status' => false,
            ]);
        }

        return response()->json([
            'data' => new QuizInstanceResource($quizInstance),
            'is_disqualified' => $player->is_disqualified,
            'is_tiebreaking' => $player->is_tiebreaking,
            'active_question_group_ended' => $this->hasQuestionGroupEnded($quizInstance),
        ]);
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

    public function compareTiebreakerAnswers(QuizInstance $quizInstance)
    {
        $players = $quizInstance->players()->where('is_tiebreaking', true)->get();
        $winningPlayer = null;
        $winningAnswer = null;
        $winningTime = null;
        foreach ($players as $player) {
            $playerAnswer = $player->player_answers()->where('question_id', $quizInstance->active_question_group_id)->first();
            // If no answer has been given, skip the player
            if ($playerAnswer == null) {
                continue;
            }
            if ($winningAnswer == null) {
                $winningPlayer = $player;
                $winningAnswer = $playerAnswer->answer_id;
                $winningTime = $playerAnswer->answered_at;
            } else {
                if ($playerAnswer->answer_id > $winningAnswer) {
                    $winningPlayer = $player;
                    $winningAnswer = $playerAnswer->answer_id;
                    $winningTime = $playerAnswer->answered_at;
                } else if ($playerAnswer->answer_id == $winningAnswer) {
                    if ($playerAnswer->answered_at < $winningTime) {
                        $winningPlayer = $player;
                        $winningAnswer = $playerAnswer->answer_id;
                        $winningTime = $playerAnswer->answered_at;
                    }
                }
            }
        }
        return response()->json([
            'winning_player' => new PlayerResource($winningPlayer),
            'winning_answer' => $winningAnswer,
            'winning_time' => $winningTime,
        ]);
    }

    public function getRandomTiebreakerQuestion(QuizInstance $quizInstance)
    {
        $questionGroup = $quizInstance->quiz->questionGroups()->where('is_additional', true)->first();
        $question = $questionGroup->questions()->inRandomOrder()->first();
        return new QuestionResource($question);
    }

    public function pollQuizInstanceOpenAnswers(QuizInstance $quizInstance)
    {
        $allOpenAnswers = [];

        // Assuming $quizInstance->players returns an array or collection of Player objects
        foreach ($quizInstance->players as $player) {
            // Assuming each player has an 'openAnswers' property or method that returns their open answers
            // Adjust the method of access according to your actual data structure
            $playerOpenAnswers = $player->open_answers; // This could be a method call or property

            // If openAnswers is a method that returns a collection or array of answers, directly merge
            // If it's a more complex structure, you might need to transform it before merging
            $allOpenAnswers = array_merge($allOpenAnswers, $playerOpenAnswers);
        }

        return $allOpenAnswers;
    }

    public function clearAllTiebreakerData(QuizInstance $quizInstance)
    {

        // Delete all tiebreaker answers
        $players = $quizInstance->players;
        foreach ($players as $player) {
            $player->is_tiebreaking = false;
            $player->save();
            // Clear all player answers where question group of question is additional
            $player->player_answers()->whereHas('question', function ($query) {
                $query->whereHas('question_group', function ($query) {
                    $query->where('is_additional', true);
                });
            })->delete();
        }

        // Clear active question group
        $quizInstance->update([
            'active_question_group_id' => null,
            'active_question_group_start' => null,
        ]);

        return response()->json();
    }
}
