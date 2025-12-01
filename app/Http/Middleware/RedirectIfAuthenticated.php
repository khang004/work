<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  ...$guards
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Redirect dựa theo guard và role
                if ($guard === 'admin' && $user->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                } elseif ($guard === 'web') {
                    if ($user->isEmployer()) {
                        return redirect()->route('employer.dashboard');
                    } elseif ($user->isCandidate()) {
                        return redirect()->route('candidate.dashboard');
                    }
                }
            }
        }

        return $next($request);
    }
}
