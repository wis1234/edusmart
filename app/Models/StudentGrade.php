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


     /**
     * Add this method to your StudentGrade model
     */
public function getPerformanceTrend()
{
    // Get previous grade for the same subject with evaluation date
    $previousGrade = StudentGrade::where('student_id', $this->student_id)
        ->whereHas('evaluation', function($q) {
            $q->where('subject_id', $this->evaluation->subject_id)
              ->where('evaluation_date', '<', $this->evaluation->evaluation_date);
        })
        ->with('evaluation') // Eager load the evaluation
        ->join('evaluations', 'student_grades.evaluation_id', '=', 'evaluations.id')
        ->orderBy('evaluations.evaluation_date', 'desc')
        ->select('student_grades.*') // Select only student grade columns
        ->first();

    if (!$previousGrade) {
        return [
            'trend' => 'new',
            'improvement_percentage' => 0,
            'previous_grade' => null
        ];
    }

    $currentPercentage = ($this->marks_obtained / $this->evaluation->total_marks) * 100;
    $previousPercentage = ($previousGrade->marks_obtained / $previousGrade->evaluation->total_marks) * 100;
    $improvement = $currentPercentage - $previousPercentage;

    // Determine trend (5% threshold for significant change)
    if ($improvement > 5) {
        $trend = 'improving';
    } elseif ($improvement < -5) {
        $trend = 'declining';
    } else {
        $trend = 'consistent';
    }

    return [
        'trend' => $trend,
        'improvement_percentage' => round($improvement, 2),
        'previous_grade' => $previousGrade->marks_obtained . '/' . $previousGrade->evaluation->total_marks
    ];
}

public function getStudentPerformanceHistory()
{
    return Evaluation::where('subject_id', $this->evaluation->subject_id)
        ->where('class_room_id', $this->evaluation->class_room_id)
        ->where('evaluation_date', '<=', $this->evaluation->evaluation_date)
        ->orderBy('evaluation_date')
        ->with(['studentGrades' => function($query) {
            $query->where('student_id', $this->student_id);
        }])
        ->get()
        ->map(function($evaluation) {
            $studentGrade = $evaluation->studentGrades->first();
            $classAverage = $evaluation->studentGrades->avg(function($grade) use ($evaluation) {
                return ($grade->marks_obtained / $evaluation->total_marks) * 100;
            });

            return [
                'date' => $evaluation->evaluation_date->format('M d, Y'),
                'score' => $studentGrade ? round(($studentGrade->marks_obtained / $evaluation->total_marks) * 100, 2) : null,
                'class_average' => round($classAverage, 2),
                'passing_threshold' => ($evaluation->passing_marks / $evaluation->total_marks) * 100
            ];
        })
        ->filter(function($item) {
            return !is_null($item['score']);
        })
        ->values()
        ->toArray();
}
    
}
