<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'name',
        'class_room_id',
        'school_id',
        'selected_parent_id',
        'admission_number',
        'roll_number',
        'admission_date',
        'parent_id',
        'date_of_birth',
        'gender',
        'blood_group',
        'address',
        'emergency_contact',
        'medical_conditions',
        'academic_year',
        'status',
        'profile_photo'
    ];

    protected $casts = [
        'admission_date' => 'date',
        'date_of_birth' => 'date',
        'medical_conditions' => 'array'
    ];

    /**
     * Get the user associated with the student
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent of the student
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the classroom of the student
     */
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    /**
     * Get the school of the student
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get all grades of the student
     */
    public function grades()
    {
        return $this->hasMany(StudentGrade::class);
    }

    /**
     * Get all evaluations the student has taken
     */
    public function evaluations()
    {
        return $this->belongsToMany(Evaluation::class, 'student_grades')
            ->withPivot('marks_obtained', 'remarks')
            ->withTimestamps();
    }

    /**
     * Get subjects through classroom
     */
    public function subjects()
    {
        return $this->hasManyThrough(
            Subject::class,
            ClassRoom::class,
            'id', // Foreign key on class_rooms table
            'id', // Foreign key on subjects table
            'class_room_id', // Local key on students table
            'subject_id' // Local key on class_room_subject table
        );
    }

    /**
     * Calculate student's GPA for a specific term or academic year
     */
    public function calculateGPA($academicYear = null, $term = null)
    {
        $grades = $this->grades()
            ->whereHas('evaluation', function ($query) use ($academicYear, $term) {
                if ($academicYear) {
                    $query->where('academic_year', $academicYear);
                }
                if ($term) {
                    $query->where('term', $term);
                }
            })
            ->with('evaluation')
            ->get();

        if ($grades->isEmpty()) {
            return 0;
        }

        $totalWeightedScore = 0;
        $totalWeight = 0;

        foreach ($grades as $grade) {
            $weight = $grade->evaluation->evaluationType->weight;
            $percentage = ($grade->marks_obtained / $grade->evaluation->total_marks) * 100;
            
            $totalWeightedScore += ($percentage * $weight);
            $totalWeight += $weight;
        }

        return $totalWeight > 0 ? $totalWeightedScore / $totalWeight : 0;
    }

    /**
     * Get the URL for the profile photo or default image
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo && Storage::disk('public')->exists($this->profile_photo)) {
            return Storage::url($this->profile_photo);
        }
        return asset('images/default-profile.png');
    }
}
