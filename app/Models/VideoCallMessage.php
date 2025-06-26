<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCallMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_call_id',
        'user_id',
        'message',
        'type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the video call this message belongs to
     */
    public function videoCall()
    {
        return $this->belongsTo(VideoCall::class);
    }

    /**
     * Get the user who sent the message
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for text messages
     */
    public function scopeText($query)
    {
        return $query->where('type', 'text');
    }

    /**
     * Scope for system messages
     */
    public function scopeSystem($query)
    {
        return $query->where('type', 'system');
    }

    /**
     * Scope for file messages
     */
    public function scopeFiles($query)
    {
        return $query->where('type', 'file');
    }

    /**
     * Check if message is from system
     */
    public function isSystem(): bool
    {
        return $this->type === 'system';
    }

    /**
     * Check if message is a file
     */
    public function isFile(): bool
    {
        return $this->type === 'file';
    }

    /**
     * Get file information if it's a file message
     */
    public function getFileInfoAttribute(): ?array
    {
        if ($this->isFile() && $this->metadata) {
            return $this->metadata;
        }
        return null;
    }
} 