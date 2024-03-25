<?php

namespace App\Http\Controllers\Api;

use App\Models\OpenAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;


class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PlayerResource::collection(Player::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlayerRequest $request)
    {
        $validated = $request->validated();
        return new PlayerResource(Player::create($validated));
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        return new PlayerResource($player);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerRequest $request, Player $player)
    {
        $validated = $request->validated();

        $player->update($validated);
        return new PlayerResource($player);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        $player->delete();
        return response()->json();
    }

    public function storeOpenAnswer(Request $request)
    {
        $validated = $request->validate([
            "answer" => "nullable",
            "player_id" => "required",
            "question_id" => "required",
        ]);
        return OpenAnswer::create($validated);
    }
}
