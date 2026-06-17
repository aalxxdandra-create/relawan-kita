<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeveloperMiddleware {
    public function handle(Request $request, Closure $next): Response {
        if (!auth()->check() || !auth()->user()->isDeveloper()) {
            abort(403, 'Akses ditolak. Hanya developer yang diizinkan.');
        }

        return $next($request);
    }
}