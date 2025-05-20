<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'profile_photo',
        'status',
        'school_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
    ];

    protected $appends = ['name'];

    /**
     * Get the user's full name.
     */
    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Get the school associated with the user
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the teacher profile if the user is a teacher
     */
    public function teacherProfile()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Get the subjects taught by the teacher
     */
    public function taughtSubjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'teacher_id', 'subject_id')
            ->withPivot('year')
            ->withTimestamps();
    }

    /**
     * Get the classrooms where the teacher teaches
     */
    public function teachingClassRooms()
    {
        return $this->belongsToMany(ClassRoom::class, 'subject_teacher', 'teacher_id', 'class_room_id')
            ->withPivot('subject_id', 'year')
            ->withTimestamps();
    }

    /**
     * Get the evaluations conducted by the teacher
     */
    public function conductedEvaluations()
    {
        return $this->hasMany(Evaluation::class, 'teacher_id');
    }

    /**
     * Get the children of the parent user
     */
    public function children()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    /**
     * Get the student profile if the user is a student
     */
    public function studentProfile()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Check if user is a teacher
     */
    public function isTeacher()
    {
        return $this->hasRole('enseignant');
    }

    /**
     * Check if user is a parent
     */
    public function isParent()
    {
        return $this->hasRole('parent');
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Get all grades given by the teacher
     */
    public function givenGrades()
    {
        return $this->hasManyThrough(
            StudentGrade::class,
            Evaluation::class,
            'teacher_id',
            'evaluation_id'
        );
    }

    /**
     * Delete the model's profile photo from storage.
     */
    public function deleteProfilePhoto(): void
    {
        if ($this->profile_photo && Storage::disk('public')->exists($this->profile_photo)) {
            Storage::disk('public')->delete($this->profile_photo);
        }
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleting(function ($user) {
            $user->deleteProfilePhoto();
        });
    }
}
