<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Evaluation extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'subject_id',
        'class_room_id',
        'evaluation_type_id',
        'teacher_id',
        'academic_year',
        'term',
        'evaluation_date',
        'total_marks',
        'passing_marks',
        'notes'
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'total_marks' => 'integer',
        'passing_marks' => 'integer'
    ];

    /**
     * Get the subject for this evaluation
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the classroom for this evaluation
     */
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    /**
     * Get the teacher who conducted this evaluation
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the evaluation type
     */
    public function evaluationType()
    {
        return $this->belongsTo(EvaluationType::class);
    }

    /**
     * Get the student grades for this evaluation
     */
    public function studentGrades()
    {
        return $this->hasMany(StudentGrade::class);
    }

    /**
     * Get the students who took this evaluation
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_grades')
            ->withPivot('marks_obtained', 'remarks')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
