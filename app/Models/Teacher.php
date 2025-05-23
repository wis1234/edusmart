<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = [
        'grade',
        'speciality',
        'subject_title',
        'user_id',
        'school_id',
        'teacher_firstname',
        'teacher_lastname',
        'teacher_email',
        'teacher_phone',
        'date_of_birth',
        'gender',
        'address',
        'status',
        'profile_photo',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Delete the profile photo from storage if it exists.
     */
    public function deleteProfilePhoto(): void
    {
        if ($this->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->profile_photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($this->profile_photo);
        }
    }

    /**
     * Get the user that owns the teacher profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    
    /**
     * Get the school that the teacher belongs to.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the subjects that the teacher teaches.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class)
                    ->withPivot(['year'])
                    ->withTimestamps();
    }

    /**
     * Get the classrooms where the teacher teaches.
     */
    public function classRooms(): BelongsToMany
    {
        return $this->belongsToMany(ClassRoom::class, 'class_room_teacher')
                    ->withPivot(['subject_id', 'year', 'start_time', 'end_time', 'days_of_week'])
                    ->withTimestamps();
    }

    /**
     * Get all subjects taught by the teacher (through class_room_teacher).
     */
    public function taughtSubjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'class_room_teacher')
                    ->distinct();
    }

    /**
     * Get all classrooms where the teacher teaches (with schedule info).
     */
    public function teachingClassRooms(): BelongsToMany
    {
        return $this->belongsToMany(ClassRoom::class, 'class_room_teacher')
                    ->withPivot(['subject_id', 'year', 'start_time', 'end_time', 'days_of_week'])
                    ->distinct();
    }

    /**
     * Get the evaluations conducted by the teacher.
     */
    public function conductedEvaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Get the grades given by the teacher.
     */
    public function givenGrades(): HasMany
    {
        return $this->hasMany(StudentGrade::class);
    }

    /**
     * Get the days of week for a specific classroom and subject.
     */
    public function getDaysOfWeek($classRoomId, $subjectId): array
    {
        $pivot = $this->classRooms()
            ->where('class_room_id', $classRoomId)
            ->where('subject_id', $subjectId)
            ->first()
            ?->pivot;

        return $pivot ? json_decode($pivot->days_of_week, true) : [];
    }

    /**
     * Get the schedule for a specific classroom and subject.
     */
    public function getSchedule($classRoomId, $subjectId): ?array
    {
        $pivot = $this->classRooms()
            ->where('class_room_id', $classRoomId)
            ->where('subject_id', $subjectId)
            ->first()
            ?->pivot;

        if (!$pivot) {
            return null;
        }

        return [
            'start_time' => $pivot->start_time,
            'end_time' => $pivot->end_time,
            'days_of_week' => json_decode($pivot->days_of_week, true)
        ];
    }

    /**
     * Get the teacher's full name from the associated user.
     */
    public function getNameAttribute(): string
    {
        return $this->user->name;
    }

    /**
     * Get the teacher's email from the associated user.
     */
    public function getEmailAttribute(): string
    {
        return $this->user->email;
    }

    /**
     * Get the teacher's phone from the associated user.
     */
    public function getPhoneAttribute(): string
    {
        return $this->user->phone;
    }

    /**
     * Get the teacher's address from the associated user.
     */
    public function getAddressAttribute(): string
    {
        return $this->user->address;
    }

    /**
     * Get the teacher's date of birth from the associated user.
     */
    public function getDateOfBirthAttribute(): ?\Illuminate\Support\Carbon
    {
        if ($this->user && $this->user->date_of_birth) {
            return \Illuminate\Support\Carbon::parse($this->user->date_of_birth);
        }
        return null;
    }

    /**
     * Get the teacher's gender from the associated user.
     */
    public function getGenderAttribute(): string
    {
        return $this->user->gender;
    }

    /**
     * Get the teacher's profile photo from the associated user.
     */
    public function getProfilePhotoAttribute(): ?string
    {
        return $this->user->profile_photo;
    }

    /**
     * Get the teacher's status from the associated user.
     */
    public function getStatusAttribute(): string
    {
        return $this->user->status;
    }
}
