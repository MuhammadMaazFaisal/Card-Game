<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameState;

class GameController extends Controller
{
    public function index()
    {
        
        if (!auth()->check()) {
            return view('welcome');
        }
        $user = auth()->user();

        $gameState = $user->gameState;

        if (!$gameState) {
            $gameState = GameState::create([
                'user_id' => $user->id,
                'collected_cards' => [],
            ]);
        }

        return view('welcome', ['gameState' => $gameState]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $gameState = $user->gameState;

        $collectedCards = $request->input('collectedCards');
        $remainingAttempts = $request->input('remainingAttempts');

        $gameState->collected_cards = $collectedCards;
        $gameState->save();

        $user->remainingAttempts = $remainingAttempts;
        $user->save();

        return response()->json(['success' => true]);
    }

    public function buyAttempts(Request $request)
    {
        $user = auth()->user();
        $attemptsToAdd = $request->input('attempts', 10);

        $user->remainingAttempts += $attemptsToAdd;
        $user->save();

        return response()->json(['success' => true, 'remainingAttempts' => $user->remainingAttempts]);
    }
}
