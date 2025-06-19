<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'type',
        'related_id',
        'related_type',
        'ip_address',
        'user_agent',
        'details'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'details' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function related()
    {
        return $this->morphTo();
    }

    public static function log($type, $description)
    {
        return static::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public function getDetailsFormatted()
    {
        if (!$this->details) {
            return null;
        }

        $details = $this->details;
        $changes = [];

        // Nouveau format : tableau 'changes'
        if (isset($details['changes']) && is_array($details['changes']) && count($details['changes'])) {
            $changes = $details['changes'];
        }
        // Ancien format : old/new
        elseif (isset($details['old']) && isset($details['new'])) {
            foreach ($details['new'] as $key => $newValue) {
                $oldValue = $details['old'][$key] ?? null;
                if ($oldValue !== $newValue) {
                    if (is_null($oldValue)) $oldValue = 'null';
                    if (is_null($newValue)) $newValue = 'null';
                    $changes[] = "$key: $oldValue → $newValue";
                }
            }
        }

        return [
            'changes' => $changes,
            'changed_by' => $details['changed_by'] ?? 'Unknown'
        ];
    }
} 