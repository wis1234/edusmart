<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileLock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Récupérer l'utilisateur cible depuis la route
        $targetUser = null;
        
        // Vérifier les différentes routes possibles
        if ($request->route('teacher')) {
            $targetUser = $request->route('teacher')->user;
        } elseif ($request->route('student')) {
            $targetUser = $request->route('student')->user;
        } elseif ($request->route('parent')) {
            $targetUser = $request->route('parent');
        }
        
        // Si on a un utilisateur cible et qu'il a verrouillé son profil
        if ($targetUser && $targetUser->profile_locked) {
            // Vérifier si l'utilisateur actuel peut voir le profil
            if (!$user->canViewProfile($targetUser)) {
                abort(403, 'This user has locked his profile.');
            }
        }
        
        return $next($request);
    }
} 