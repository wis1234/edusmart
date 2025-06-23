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
        $user = Auth::user();
        
        if ($user->two_factor_enabled) {
            $user->two_factor_enabled = false;
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->save();
            
            $user->notify(new \App\Notifications\TwoFactorDisabled());
            
            return back()->with('success', 'Two-factor authentication has been disabled.');
        } else {
            $user->two_factor_enabled = true;
            $user->save();
            
            $user->generateTwoFactorCode();
            $user->notify(new \App\Notifications\TwoFactorEnabled());
            
            return back()->with('success', 'Two-factor authentication has been enabled. Please check your email for the verification code.');
        }
    }

    /**
     * Toggle profile lock.
     */
    public function toggleProfileLock(Request $request)
    {
        $user = Auth::user();
        
        $isLocked = $user->toggleProfileLock();
        
        if ($isLocked) {
            return back()->with('success', 'Your profile has been locked. Only admins and school admins can view your profile details.');
        } else {
            return back()->with('success', 'Your profile has been unlocked. Other users can now view your profile details.');
        }
    }
}
