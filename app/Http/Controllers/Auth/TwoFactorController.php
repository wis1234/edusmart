<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountLocked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TwoFactorController extends Controller
{
    public function index()
    {
        if (!session('two_factor:user:id')) {
            return redirect()->route('login');
        }
        $user = \App\Models\User::find(session('two_factor:user:id'));
        $expiresAt = null;
        if ($user && $user->two_factor_expires_at) {
            $expiresAt = $user->two_factor_expires_at instanceof \Carbon\Carbon
                ? $user->two_factor_expires_at->timestamp
                : \Carbon\Carbon::parse($user->two_factor_expires_at)->timestamp;
        }
        return view('auth.two-factor', compact('expiresAt'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|digits:6',
        ]);

        $userId = session('two_factor:user:id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        // Si le compte est déjà verrouillé, on n'essaie même pas
        if ($user->locked_at) {
            return view('auth.locked');
        }
        
        if (!$user->two_factor_code || !$user->two_factor_expires_at || now()->gt($user->two_factor_expires_at)) {
            return back()->withErrors(['two_factor_code' => 'The verification code has expired. Please request a new one.']);
        }

        if ($request->two_factor_code !== $user->two_factor_code) {
            $user->two_factor_attempts += 1;
            
            if ($user->two_factor_attempts >= 3) {
                $user->locked_at = now();
                $user->save();
                $user->notify(new AccountLocked());
                return view('auth.locked');
            }

            $user->save();

            $remaining_attempts = 3 - $user->two_factor_attempts;
            $plural = $remaining_attempts > 1 ? 'attempts' : 'attempt';

            return back()->withErrors(['two_factor_code' => "Invalid code. You have {$remaining_attempts} {$plural} remaining."]);
        }

        // Reset code, attempts and log in
        $user->resetTwoFactorCode();
        $user->two_factor_attempts = 0;
        $user->save();

        Auth::login($user);
        Session::forget('two_factor:user:id');

        return redirect()->intended(route('dashboard'));
    }

    public function resend()
    {
        $user = User::find(session('two_factor:user:id'));

        if (!$user) {
            return redirect()->route('login');
        }

        // Réinitialiser les tentatives lors du renvoi
        $user->two_factor_attempts = 0;
        $user->save();

        $user->generateTwoFactorCode();
        $user->notify(new \App\Notifications\TwoFactorCode());

        return redirect()->route('two-factor.index')->with('status', 'A new verification code has been sent to your email.');
    }
} 