<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menangani proses login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            switch ($role) {
                case 'owner':
                    return redirect()->intended(route('pages.owner.dashboard'));
                case 'general_manager':
                    return redirect()->intended(route('pages.general-manager.dashboard'));
                case 'admin':
                    return redirect()->intended(route('pages.admin.dashboard'));
                case 'cashier':
                    return redirect()->intended(route('pages.kasir.dashboard'));
                default:
                    return redirect('/');
            }
        }

        return back()->withErrors([
            'login' => 'Username atau kata sandi tidak valid.',
        ])->onlyInput('username');
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
