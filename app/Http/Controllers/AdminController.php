<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::where('userType', 1)->get();

        return view('dashboard', compact('users'));
    }

    public function increaseAttempts(Request $request, User $user)
    {
        $attemptsToAdd = $request->input('attempts', 10);
        $user->remainingAttempts += $attemptsToAdd;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Attempts increased successfully.');
    }
}
