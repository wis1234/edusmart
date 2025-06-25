<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Activity;
use App\Models\User;
use App\Notifications\AccountLocked;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login', [
            'canResetPassword' => \Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse|View
    {
        $user = User::where('email', $request->email)->first();

        // Vérifier si le compte est verrouillé
        if ($user && $user->locked_at) {
            return view('auth.locked');
        }

        try {
            $request->authenticate();

            // Réinitialiser les tentatives en cas de succès
            if ($user) {
                $user->login_attempts = 0;
                $user->save();
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Incrémenter les tentatives de connexion
            if ($user) {
                $user->login_attempts += 1;
                $user->save();

                // Verrouiller le compte après 3 tentatives
                if ($user->login_attempts >= 3) {
                    $user->locked_at = now();
                    $user->save();

                    // Envoyer l'email de notification
                    $user->notify(new AccountLocked());
                    
                    // Renvoyer la vue du compte verrouillé
                    return view('auth.locked');
                }
            }

            // Log failed login
            Activity::create([
                'user_id' => null,
                'type' => 'login_failed',
                'description' => 'Échec de connexion pour l\'email : ' . $request->input('email'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            throw $e;
        }

        // Log successful login
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'login',
            'description' => 'Connexion réussie',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $request->session()->regenerate();

        // 2FA: Generate and send code, store user ID in session, logout user
        $user = auth()->user();
        if ($user) { // S'assurer que l'utilisateur est bien authentifié
            if ($user->two_factor_enabled) {
                $user->two_factor_attempts = 0; // Reset counter
                $user->save();
                $user->generateTwoFactorCode();
                $user->notify(new \App\Notifications\TwoFactorCode());
                session(['two_factor:user:id' => $user->id]);
                auth()->logout(); // On déconnecte pour la vérification 2FA

                return redirect()->route('two-factor.index');
            } else {
                // 2FA not enabled, proceed directly to dashboard
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
        // Log logout
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
