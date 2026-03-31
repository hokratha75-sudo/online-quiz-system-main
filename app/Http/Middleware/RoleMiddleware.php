<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed ...$roles (Admin=1, Teacher=2, Student=3)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Admin always has access
        if ($user && (int)$user->role_id === 1) {
            return $next($request);
        }

        // Check if user role matches one of the required roles
        if ($user && in_array((int)$user->role_id, array_map('intval', $roles))) {
            return $next($request);
        }

        // If unauthorized, redirect based on role
        if ($user) {
            if ((int)$user->role_id === 1) return redirect()->route('dashboard');
            if ((int)$user->role_id === 2) return redirect()->route('dashboard');
            if ((int)$user->role_id === 3) return redirect()->route('students.dashboard');
        }

        return response()->view('errors.403', ['message' => 'Unauthorized action.'], 403);
    }
}
