<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCallActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_call_id',
        'user_id',
        'action',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $appends = ['action_description'];

    /**
     * Get the video call this activity belongs to
     */
    public function videoCall()
    {
        return $this->belongsTo(VideoCall::class);
    }

    /**
     * Get the user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for join actions
     */
    public function scopeJoins($query)
    {
        return $query->where('action', 'joined');
    }

    /**
     * Scope for leave actions
     */
    public function scopeLeaves($query)
    {
        return $query->where('action', 'left');
    }

    /**
     * Scope for mute actions
     */
    public function scopeMutes($query)
    {
        return $query->whereIn('action', ['muted', 'unmuted']);
    }

    /**
     * Scope for video actions
     */
    public function scopeVideoActions($query)
    {
        return $query->whereIn('action', ['video_on', 'video_off']);
    }

    /**
     * Scope for screen sharing actions
     */
    public function scopeScreenSharing($query)
    {
        return $query->whereIn('action', ['screen_shared', 'screen_stopped']);
    }

    /**
     * Get human readable action description
     */
    public function getActionDescriptionAttribute(): string
    {
        $userName = $this->user ? $this->user->name : 'Unknown User';
        
        return match($this->action) {
            'joined' => "{$userName} a rejoint l'appel",
            'left' => "{$userName} a quitté l'appel",
            'muted' => "{$userName} a coupé le micro",
            'unmuted' => "{$userName} a rallumé le micro",
            'video_on' => "{$userName} a allumé la caméra",
            'video_off' => "{$userName} a éteint la caméra",
            'screen_shared' => "{$userName} a commencé le partage d'écran",
            'screen_stopped' => "{$userName} a arrêté le partage d'écran",
            default => "{$userName} a effectué une action: {$this->action}",
        };
    }
} 