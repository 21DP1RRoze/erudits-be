<?php

namespace App\Http\Controllers\Api;

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
        return new PlayerRequest(Player::create($validated));
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        return new Player($player);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerRequest $request, Player $player)
    {
        $validated = $request->validated();

        $player->update($validated);
        return new player($player);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(player $player)
    {
        $player->delete();
        return response()->json();
    }
}
