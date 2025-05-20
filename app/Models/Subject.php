<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'credits',
        'level',
        'hours_per_week',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credits' => 'integer',
        'hours_per_week' => 'integer'
    ];

    /**
     * Get the classrooms where this subject is taught
     */
    public function classRooms()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_room_subject')
            ->withPivot('year', 'hours_per_week', 'is_mandatory')
            ->withTimestamps();
    }

    /**
     * Get the teachers who teach this subject
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'subject_teacher')
            ->withPivot('class_room_id', 'year')
            ->withTimestamps();
    }

    /**
     * Get all evaluations for this subject
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Get the student grades for this subject
     */
    public function studentGrades()
    {
        return $this->hasManyThrough(StudentGrade::class, Evaluation::class);
    }
}
