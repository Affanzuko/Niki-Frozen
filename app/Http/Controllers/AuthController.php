<?php

namespace App\Http\Controllers;

use App\Models\CashierSession;
use Carbon\Carbon;
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

            $user = Auth::user();
            $role = $user->role;

            if ($role === 'cashier') {
                $now = Carbon::now();
                $time = $now->format('H:i');

                if ($time < '07:00' || $time >= '21:00') {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect('/login')->with('error_popup', 'Anda tidak dapat login karena berada diluar jam sesi kasir (07:00 - 21:00).');
                }

                $activeSession = CashierSession::where('user_id', $user->id)
                    ->whereDate('date', today())
                    ->whereNull('ended_at')
                    ->first();

                if ($activeSession) {
                    $request->session()->put('cashier_session_id', $activeSession->id);
                } else {
                    $now = Carbon::now();
                    $time = $now->format('H:i');
                    
                    $shift = ($time < '13:30') ? 'pagi' : 'siang';

                    $newSession = CashierSession::create([
                        'user_id' => $user->id,
                        'branch_id' => $user->branch_id ?? 1,
                        'shift' => $shift,
                        'date' => today(),
                        'started_at' => now(),
                    ]);

                    $request->session()->put('cashier_session_id', $newSession->id);
                }
            }

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
        if (Auth::check() && Auth::user()->role === 'cashier') {
            $sessionId = $request->session()->get('cashier_session_id');
            if ($sessionId) {
                $session = CashierSession::find($sessionId);
                if ($session && is_null($session->ended_at)) {
                    $session->update(['ended_at' => now()]);
                }
            } else {
                CashierSession::where('user_id', Auth::id())
                    ->whereDate('date', today())
                    ->whereNull('ended_at')
                    ->update(['ended_at' => now()]);
            }
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
