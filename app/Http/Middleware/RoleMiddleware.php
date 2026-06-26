<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'))->withErrors([
                'login' => 'Silakan login terlebih dahulu untuk mengakses halaman ini.'
            ]);
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            // Tampilkan halaman error 403
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
