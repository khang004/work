<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAdminSession
{
    /**
     * Handle an incoming request - Phải chạy TRƯỚC StartSession middleware
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set tên cookie session riêng cho admin
        // Điều này phải được set TRƯỚC khi StartSession middleware chạy
        config(['session.cookie' => config('session.cookie') . '_admin']);
        
        return $next($request);
    }
}
