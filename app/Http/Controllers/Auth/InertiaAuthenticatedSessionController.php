<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Activity;
use App\Models\User;
use App\Notifications\AccountLocked;

class InertiaAuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => \Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->email)->first();

        if ($user && $user->locked_at) {
            return Inertia::render('Auth/Locked');
        }

        try {
            $request->authenticate();

            if ($user) {
                $user->login_attempts = 0;
                $user->save();
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($user) {
                $user->login_attempts += 1;
                $user->save();

                if ($user->login_attempts >= 3) {
                    $user->locked_at = now();
                    $user->save();

                    $user->notify(new AccountLocked());

                    return Inertia::render('Auth/Locked');
                }
            }

            Activity::create([
                'user_id' => null,
                'type' => 'login_failed',
                'description' => 'Échec de connexion pour l\'email : ' . $request->input('email'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            throw $e;
        }

        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'login',
            'description' => 'Connexion réussie',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $request->session()->regenerate();

        $user = auth()->user();
        if ($user) {
            if ($user->two_factor_enabled) {
                $user->two_factor_attempts = 0;
                $user->save();
                $user->generateTwoFactorCode();
                $user->notify(new \App\Notifications\TwoFactorCode());
                session(['two_factor:user:id' => $user->id]);
                auth()->logout();

                return redirect()->route('two-factor.index');
            } else {
                return redirect()->intended(route('dashboard'));
            }
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'logout',
            'description' => 'Déconnexion',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
