<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request) {
        $validated = $request->validate([
            'username' => 'required|string|max:12',
            'password' => 'required|confirmed',
        ]);
        return response()->json(User::create($validated));
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'username' => 'required|string|max:12',
            'password' => 'required',
        ]);

        if (Auth::attempt($validated)) {
            return response()->json([
                'token' => auth()->user()->createToken('login')->plainTextToken,
            ]);
        }

        return response()->json([
            'message' => 'Unauthenticated.',
        ], 401);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json();
    }
}
