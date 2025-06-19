<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $path = $request->path();
        $method = $request->method();
        
        // Log specific routes
        if ($path === 'login' && $method === 'POST') {
            if (Auth::check()) {
                Activity::log('login', 'User logged in successfully');
            } else {
                Activity::create([
                    'type' => 'login_failed',
                    'description' => 'Failed login attempt with email: ' . $request->input('email'),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            }
        }
        elseif ($path === 'logout' && $method === 'POST' && Auth::check()) {
            Activity::log('logout', 'User logged out');
        }
        elseif ($path === 'password/email' && $method === 'POST') {
            Activity::create([
                'type' => 'password_reset_request',
                'description' => 'Password reset requested for email: ' . $request->input('email'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }
        elseif ($path === 'password/reset' && $method === 'POST') {
            Activity::create([
                'type' => 'password_reset',
                'description' => 'Password was reset for email: ' . $request->input('email'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }
    }
} 