<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    // public function handle(Request $request, Closure $next, string $role): Response
    // {
    //     // dd("Middleware role dijalankan untuk role: " . $role);
    //     if (auth()->check() && auth()->user()->role === $role) {
    //         return $next($request);
    //     }

    //     abort(403, 'Unauthorized access');
    // }

    // baru 
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (auth()->check() && auth()->user()->role === $role) {
            return $next($request);
        }

        // dd(auth()->user()->role);

        abort(403, 'Unauthorized access');
    }
}
