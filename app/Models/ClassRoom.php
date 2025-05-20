<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'school_id',
        'grade_level',
        'section',
        'academic_year',
        'capacity',
        'start_time',
        'end_time',
        'days_of_week',
        'room_number',
        'building',
        'floor',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the school that owns the classroom.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the subjects for the classroom.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_room_subject')
            ->withPivot('teacher_id', 'year', 'hours_per_week', 'is_mandatory')
            ->withTimestamps();
    }

    /**
     * Get the teachers for the classroom through subject_teacher pivot.
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'subject_teacher', 'class_room_id', 'teacher_id')
            ->withPivot('subject_id', 'year')
            ->withTimestamps();
    }

    /**
     * Get the students in the classroom.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the evaluations for the classroom.
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Get the user who created the classroom.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the classroom.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
