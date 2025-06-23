<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class ProfileHelper
{
    /**
     * Vérifier si l'utilisateur actuel peut voir le profil d'un autre utilisateur
     */
    public static function canViewProfile($targetUser)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser || !$targetUser) {
            return false;
        }
        
        // Si le profil n'est pas verrouillé, on peut le voir
        if (!$targetUser->profile_locked) {
            return true;
        }
        
        // Si le profil est verrouillé, seuls les admins et school admins peuvent le voir
        return $currentUser->hasRole(['admin', 'school_admin']);
    }
    
    /**
     * Vérifier si un lien vers un profil doit être affiché
     */
    public static function shouldShowProfileLink($targetUser)
    {
        return self::canViewProfile($targetUser);
    }
} 