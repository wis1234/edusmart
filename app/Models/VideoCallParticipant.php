<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCallParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_call_id',
        'user_id',
        'role',
        'status',
        'joined_at',
        'left_at',
        'is_muted',
        'is_video_off',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'is_muted' => 'boolean',
        'is_video_off' => 'boolean',
    ];

    /**
     * Get the video call this participant belongs to
     */
    public function videoCall()
    {
        return $this->belongsTo(VideoCall::class);
    }

    /**
     * Get the user who is participating
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Join the call
     */
    public function join(): void
    {
        $this->update([
            'status' => 'joined',
            'joined_at' => now(),
        ]);
    }

    /**
     * Leave the call
     */
    public function leave(): void
    {
        $this->update([
            'status' => 'left',
            'left_at' => now(),
        ]);
    }

    /**
     * Decline the call invitation
     */
    public function decline(): void
    {
        $this->update([
            'status' => 'declined',
        ]);
    }

    /**
     * Toggle mute status
     */
    public function toggleMute(): void
    {
        $this->update([
            'is_muted' => !$this->is_muted,
        ]);
    }

    /**
     * Toggle video status
     */
    public function toggleVideo(): void
    {
        $this->update([
            'is_video_off' => !$this->is_video_off,
        ]);
    }

    /**
     * Check if participant is currently in the call
     */
    public function isInCall(): bool
    {
        return $this->status === 'joined';
    }

    /**
     * Check if participant is muted
     */
    public function isMuted(): bool
    {
        return $this->is_muted;
    }

    /**
     * Check if participant has video off
     */
    public function hasVideoOff(): bool
    {
        return $this->is_video_off;
    }

    /**
     * Check if participant is the host
     */
    public function isHost(): bool
    {
        return $this->role === 'host';
    }

    /**
     * Get the duration the participant was in the call
     */
    public function getCallDurationAttribute(): int
    {
        if (!$this->joined_at) {
            return 0;
        }

        $endTime = $this->left_at ?? now();
        return $this->joined_at->diffInSeconds($endTime);
    }

    /**
     * Get formatted call duration
     */
    public function getFormattedCallDurationAttribute(): string
    {
        $duration = $this->call_duration;
        
        if ($duration === 0) {
            return '00:00';
        }

        $hours = floor($duration / 3600);
        $minutes = floor(($duration % 3600) / 60);
        $seconds = $duration % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Scope for active participants
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'joined');
    }

    /**
     * Scope for invited participants
     */
    public function scopeInvited($query)
    {
        return $query->where('status', 'invited');
    }

    /**
     * Scope for participants who left
     */
    public function scopeLeft($query)
    {
        return $query->where('status', 'left');
    }

    /**
     * Scope for hosts
     */
    public function scopeHosts($query)
    {
        return $query->where('role', 'host');
    }
} 