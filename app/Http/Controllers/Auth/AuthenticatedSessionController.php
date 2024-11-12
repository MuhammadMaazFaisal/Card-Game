<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return redirect('/');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
{
    $request->validate([
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ]);

    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    $request->session()->regenerate();

    $user = Auth::user();

    if ($user->userType == 2) {
        // Admin users should not login here
        Auth::logout();
        return redirect()->back()->withErrors([
            'email' => 'Please use the admin login page.',
        ]);
    } else {
        // Regular user
        return redirect()->intended('/');
    }
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
