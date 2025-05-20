<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'weight',
        'description'
    ];

    protected $casts = [
        'weight' => 'integer'
    ];

    /**
     * Get all evaluations of this type
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Get the average weight of this evaluation type across all subjects
     */
    public function getAverageScoreAttribute()
    {
        return $this->evaluations()
            ->join('student_grades', 'evaluations.id', '=', 'student_grades.evaluation_id')
            ->avg('student_grades.marks_obtained');
    }

    /**
     * Get the pass rate for this evaluation type
     */
    public function getPassRateAttribute()
    {
        $total = $this->evaluations()
            ->join('student_grades', 'evaluations.id', '=', 'student_grades.evaluation_id')
            ->count();

        if ($total === 0) {
            return 0;
        }

        $passed = $this->evaluations()
            ->join('student_grades', 'evaluations.id', '=', 'student_grades.evaluation_id')
            ->whereRaw('student_grades.marks_obtained >= evaluations.passing_marks')
            ->count();

        return ($passed / $total) * 100;
    }
}
