<?php

namespace App\Http\Controllers\Api;

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
        return new QuizInstanceRequest(QuizInstance::create($validated));
    }

    /**
     * Display the specified resource.
     */
    public function show(QuizInstance $quizInstance)
    {
        return new QuizInstance($quizInstance);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuizInstanceRequest $request, QuizInstance $quizInstance)
    {
        $validated = $request->validated();
        
        $quizInstance->update($validated);
        return new quizInstance($quizInstance);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuizInstance $quizInstance)
    {
        $quizInstance->delete();
        return response()->json();
    }
}
