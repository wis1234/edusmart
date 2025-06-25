<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendar extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'class_room_id',
        'subject_id',
        'teacher_id',
        'weekday',
        'start_time',
        'end_time',
        'academic_year',
        'week_number',
        'created_by',
        'updated_by',
        'week_schedule',
    ];

    protected $casts = [
        'week_schedule' => 'array',
    ];

    /**
     * Get the school for this schedule.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the class room for this schedule.
     */
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    /**
     * Get the subject for this schedule.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher for this schedule.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the user who created this calendar entry.
     */
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Get the user who last updated this calendar entry.
     */
    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    /**
     * Check if there is a schedule conflict
     *
     * @param array $attributes
     * @return bool
     */
    public static function hasConflict($attributes)
    {
        $query = self::query()
            ->where('class_room_id', $attributes['class_room_id'])
            ->where('weekday', $attributes['weekday'])
            ->where('academic_year', $attributes['academic_year']);

        if (!empty($attributes['week_number'])) {
            $query->where('week_number', $attributes['week_number']);
        } else {
            $query->whereNull('week_number');
        }

        // If updating an existing record, exclude it from the check
        if (!empty($attributes['id'])) {
            $query->where('id', '!=', $attributes['id']);
        }

        // Check for time overlap
        $query->where(function($q) use ($attributes) {
            $q->where(function($q) use ($attributes) {
                $q->where('start_time', '<', $attributes['end_time'])
                  ->where('end_time', '>', $attributes['start_time']);
            });
        });

        // Check for teacher availability
        $teacherConflict = self::query()
            ->where('teacher_id', $attributes['teacher_id'])
            ->where('weekday', $attributes['weekday'])
            ->where('academic_year', $attributes['academic_year']);

        if (!empty($attributes['week_number'])) {
            $teacherConflict->where('week_number', $attributes['week_number']);
        } else {
            $teacherConflict->whereNull('week_number');
        }

        if (!empty($attributes['id'])) {
            $teacherConflict->where('id', '!=', $attributes['id']);
        }

        $teacherConflict->where(function($q) use ($attributes) {
            $q->where(function($q) use ($attributes) {
                $q->where('start_time', '<', $attributes['end_time'])
                  ->where('end_time', '>', $attributes['start_time']);
            });
        });

        return $query->exists() || $teacherConflict->exists();
    }
}
