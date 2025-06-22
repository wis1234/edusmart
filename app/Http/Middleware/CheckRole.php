<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user() || !$request->user()->hasAnyRole($roles)) {
            abort(403, 'Unauthorized action.');
        }

        // Vérifier le statut de l'école pour les school admins
        if ($request->user()->role === 'school_admin' && $request->user()->school_id) {
            $school = \App\Models\School::find($request->user()->school_id);
            if (!$school || $school->status !== 'active') {
                abort(403, 'Your school is not active. Please contact the administrator.');
            }
        }

        return $next($request);
    }
} 