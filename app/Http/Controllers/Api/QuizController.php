<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuizRequest;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Add pagination if necessary
        return QuizResource::collection(Quiz::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuizRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        return new QuizResource(Quiz::create($validated));
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        return new QuizResource($quiz);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuizRequest $request, Quiz $quiz)
    {
        $validated = $request->validated();
        $quiz->update($validated);
        return new QuizResource($quiz);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return response()->json();
    }

    public function saveQuiz(Request $request, Quiz $quiz)
    {
        $jsonData = $request->json()->all(); // Retrieve JSON data from the request

        // Update the quiz details
        $quiz->title = $jsonData['title'];
        $quiz->description = $jsonData['description'];
        $quiz->save();

        // Update or create question groups
        foreach ($jsonData['question_groups'] as $groupData) {
            $group = $quiz->questionGroups()->updateOrCreate(
                ['id' => $groupData['id']], // Update if exists, otherwise create
                [
                    'title' => $groupData['title'],
                    'disqualify_amount' => $groupData['disqualify_amount'],
                    'points' => $groupData['points'],
                    'answer_time' => $groupData['answer_time'],
                    // Add other group fields here
                ]
            );

            // Update or create questions for each group
            foreach ($groupData['questions'] as $questionData) {
                $question = $group->questions()->updateOrCreate(
                    ['id' => $questionData['id']], // Update if exists, otherwise create
                    [
                        'text' => $questionData['text'],
                        'is_open_answer' => $questionData['is_open_answer'],
                        // Add other question fields here
                    ]
                );

                // Update or create answers for each question
                foreach ($questionData['answers'] as $answerData) {
                    $question->answers()->updateOrCreate(
                        ['id' => $answerData['id']], // Update if exists, otherwise create
                        [
                            'text' => $answerData['text'],
                            'is_correct' => $answerData['is_correct'],
                            // Add other answer fields here
                        ]
                    );
                }
            }
        }
    }
}
