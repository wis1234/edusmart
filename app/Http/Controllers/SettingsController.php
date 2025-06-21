<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $activeTab = $request->get('tab', 'two-factor');
        
        // If it's an AJAX request, return only the tab content
        if ($request->ajax()) {
            $view = match($activeTab) {
                'two-factor' => 'settings.partials.two-factor',
                'profile' => 'settings.partials.profile',
                'security' => 'settings.partials.security',
                'notifications' => 'settings.partials.notifications',
                default => 'settings.partials.two-factor'
            };
            
            return view('settings.index', compact('user', 'activeTab'))->render();
        }
        
        return view('settings.index', compact('user', 'activeTab'));
    }

    /**
     * Toggle two-factor authentication.
     */
    public function toggleTwoFactor(Request $request)
    {
        try {
            $user = Auth::user();
            $enabled = $request->input('enabled', false);
            
            if ($enabled) {
                // Activer le 2FA
                $user->two_factor_enabled = true;
                $user->save();
                
                // Envoyer notification d'activation
                try {
                    $user->notify(new \App\Notifications\TwoFactorEnabled());
                } catch (\Exception $e) {
                    Log::error('Failed to send 2FA enabled notification: ' . $e->getMessage());
                    // Continue even if notification fails
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Two-factor authentication has been enabled for your account.',
                    'enabled' => true
                ]);
            } else {
                // Désactiver le 2FA
                $user->two_factor_enabled = false;
                $user->two_factor_code = null;
                $user->two_factor_expires_at = null;
                $user->save();
                
                // Envoyer notification de désactivation
                try {
                    $user->notify(new \App\Notifications\TwoFactorDisabled());
                } catch (\Exception $e) {
                    Log::error('Failed to send 2FA disabled notification: ' . $e->getMessage());
                    // Continue even if notification fails
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Two-factor authentication has been disabled for your account.',
                    'enabled' => false
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error toggling 2FA: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating your settings. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
