<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PlayerResource;
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
}
