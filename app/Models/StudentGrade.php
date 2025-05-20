<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentGrade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'evaluation_id',
        'student_id',
        'marks_obtained',
        'remarks'
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2'
    ];

    /**
     * Get the evaluation this grade belongs to
     */
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    /**
     * Get the student this grade belongs to
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the subject through evaluation
     */
    public function subject()
    {
        return $this->hasOneThrough(
            Subject::class,
            Evaluation::class,
            'id', // Foreign key on evaluations table
            'id', // Foreign key on subjects table
            'evaluation_id', // Local key on student_grades table
            'subject_id' // Local key on evaluations table
        );
    }

    /**
     * Calculate the percentage score
     */
    public function getPercentageAttribute()
    {
        if ($this->evaluation && $this->evaluation->total_marks > 0) {
            return ($this->marks_obtained / $this->evaluation->total_marks) * 100;
        }
        return 0;
    }

    /**
     * Check if the grade is a passing grade
     */
    public function getIsPassingAttribute()
    {
        if ($this->evaluation) {
            return $this->marks_obtained >= $this->evaluation->passing_marks;
        }
        return false;
    }
}
