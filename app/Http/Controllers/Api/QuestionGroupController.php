<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionGroupRequest;
use App\Models\QuestionGroup;
use Illuminate\Http\Request;

class QuestionGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return QuestionGroup::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionGroupRequest $request)
    {
        $validated = $request->validated();
        return QuestionGroup::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(QuestionGroup $questionGroup)
    {
        return $questionGroup;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuestionGroupRequest $request, QuestionGroup $questionGroup)
    {
        $validated = $request->validated();
        $questionGroup->update($validated);
        return $questionGroup;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionGroup $questionGroup)
    {
        $questionGroup->delete();
        return response()->json();
    }
}
