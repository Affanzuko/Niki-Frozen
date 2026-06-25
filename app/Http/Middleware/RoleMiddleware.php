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
            // Redirect based on role if they try to access unauthorized pages
            switch ($user->role) {
                case 'owner':
                    return redirect()->route('pages.owner.dashboard');
                case 'general_manager':
                    return redirect()->route('pages.general-manager.dashboard');
                case 'admin':
                    return redirect()->route('pages.admin.dashboard');
                case 'cashier':
                    return redirect()->route('pages.kasir.dashboard');
                default:
                    return redirect('/login');
            }
        }

        return $next($request);
    }
}
