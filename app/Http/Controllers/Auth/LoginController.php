<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // resources/views/auth/login.blade.php
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $throttleKey = Str::lower($request->input('username')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'username' => "Too many attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password_hash)) {
            RateLimiter::clear($throttleKey);
            Auth::login($user);

            if ((int)$user->role_id === 1) {
                return redirect()->route('admin.dashboard');
            } elseif ((int)$user->role_id === 3) {
                return redirect()->route('students.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }

        RateLimiter::hit($throttleKey);

        return back()->withErrors([
            'username' => 'Invalid username or password',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
