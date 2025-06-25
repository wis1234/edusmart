<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VideoCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'title',
        'description',
        'type',
        'status',
        'initiator_id',
        'school_id',
        'participants',
        'started_at',
        'ended_at',
        'duration',
        'settings',
    ];

    protected $casts = [
        'participants' => 'array',
        'settings' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Boot the model and generate room_id if not set
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($videoCall) {
            if (empty($videoCall->room_id)) {
                $videoCall->room_id = self::generateRoomId();
            }
        });
    }

    /**
     * Generate a unique room ID
     */
    public static function generateRoomId(): string
    {
        do {
            $roomId = Str::random(12);
        } while (self::where('room_id', $roomId)->exists());

        return $roomId;
    }

    /**
     * Get the initiator of the call
     */
    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    /**
     * Get the school associated with the call
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get all participants in the call
     */
    public function participants()
    {
        return $this->hasMany(VideoCallParticipant::class);
    }

    /**
     * Get active participants (currently in the call)
     */
    public function activeParticipants()
    {
        return $this->participants()->where('status', 'joined');
    }

    /**
     * Get invited participants
     */
    public function invitedParticipants()
    {
        return $this->participants()->where('status', 'invited');
    }

    /**
     * Check if a user is a participant in this call
     */
    public function hasParticipant(User $user): bool
    {
        return $this->participants()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if a user is the host of this call
     */
    public function isHost(User $user): bool
    {
        return $this->participants()
            ->where('user_id', $user->id)
            ->where('role', 'host')
            ->exists();
    }

    /**
     * Start the call
     */
    public function start(): void
    {
        $this->update([
            'status' => 'active',
            'started_at' => now(),
        ]);
    }

    /**
     * End the call
     */
    public function end(): void
    {
        $endTime = now();
        $duration = $this->started_at ? $this->started_at->diffInSeconds($endTime) : 0;

        $this->update([
            'status' => 'ended',
            'ended_at' => $endTime,
            'duration' => $duration,
        ]);

        // Mark all participants as left
        $this->participants()->update([
            'status' => 'left',
            'left_at' => now(),
        ]);
    }

    /**
     * Cancel the call
     */
    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'ended_at' => now(),
        ]);
    }

    /**
     * Get the formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration) {
            return '00:00';
        }

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Check if the call is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the call is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the call is ended
     */
    public function isEnded(): bool
    {
        return in_array($this->status, ['ended', 'cancelled']);
    }

    /**
     * Scope for active calls
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for pending calls
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for calls by school
     */
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Scope for calls initiated by user
     */
    public function scopeInitiatedBy($query, $userId)
    {
        return $query->where('initiator_id', $userId);
    }

    /**
     * Scope for calls where user is participant
     */
    public function scopeWhereParticipant($query, $userId)
    {
        return $query->whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
} 