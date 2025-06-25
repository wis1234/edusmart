<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class ParentModel extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'parents';

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'profile_photo',
        'status',
        'student_id',
        'student_grade_id',
        'subject_id',
    ];

    // Relationship: Parent belongs to a Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relationship: Parent has many StudentGrades
    public function studentGrades()
    {
        return $this->hasMany(StudentGrade::class, 'parent_id');
    }

    // Relationship: Parent can access evaluations through studentGrades
    public function evaluations()
    {
        return $this->hasManyThrough(
            Evaluation::class,
            StudentGrade::class,
            'parent_id',      // Foreign key on student_grades table...
            'id',             // Foreign key on evaluations table (primary key)
            'id',             // Local key on parents table
            'evaluation_id'   // Local key on student_grades table
        );
    }

    // Relationship: Parent belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
