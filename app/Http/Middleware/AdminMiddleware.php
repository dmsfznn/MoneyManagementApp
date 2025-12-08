<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // If user is logged in but not admin, redirect to user dashboard
        if (Auth::check()) {
            return redirect()->route('user.dashboard')->with('error', 'You do not have admin access.');
        }

        // If user is not logged in, redirect to login
        return redirect()->route('login')->with('error', 'Please login first.');
    }
}
