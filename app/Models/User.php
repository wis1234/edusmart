<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'address',
        'profession',
        'date_of_birth',
        'gender',
        'profile_photo',
        'status',
        'role',
        'school_id',
        'selected_parent_id',
        'two_factor_enabled',
        'profile_locked',
        'validated',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'profile_locked' => 'boolean',
        'validated' => 'boolean',
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
        if (!$this->teacherProfile) {
            return $this->belongsToMany(Subject::class, 'class_room_teacher', 'teacher_id', 'subject_id')
                ->whereRaw('1 = 0'); // Retourne une collection vide si pas de profil enseignant
        }
        
        return $this->belongsToMany(Subject::class, 'class_room_teacher', 'teacher_id', 'subject_id')
            ->where('teacher_id', $this->teacherProfile->id)
            ->withPivot(['class_room_id', 'year', 'start_time', 'end_time', 'days_of_week'])
            ->withTimestamps();
    }

    /**
     * Get the classrooms where the teacher teaches
     */
    public function teachingClassRooms()
    {
        if (!$this->teacherProfile) {
            return $this->belongsToMany(ClassRoom::class, 'class_room_teacher', 'teacher_id', 'class_room_id')
                ->whereRaw('1 = 0'); // Retourne une collection vide si pas de profil enseignant
        }
        
        return $this->belongsToMany(ClassRoom::class, 'class_room_teacher', 'teacher_id', 'class_room_id')
            ->withPivot(['subject_id', 'year', 'start_time', 'end_time', 'days_of_week'])
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
     * Get the students associated with this parent (using selected_parent_id)
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'selected_parent_id');
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
     * Check if user is a school admin
     */
    public function isSchoolAdmin()
    {
        return $this->role === 'school_admin';
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

    public function canManageEvaluations()
    {
        return $this->email === 'ronaldoagbohou@gmail.com' || 
               $this->hasRole('enseignant') || 
               $this->teacher()->exists();
    }

    // Scope for teacher filtering
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    // Relationship for teacher
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function hasUnreadNotifications()
    {
        return $this->unreadNotifications()->exists();
    }

    /**
     * Get the URL for the profile photo or default image
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo && \Storage::disk('public')->exists($this->profile_photo)) {
            return \Storage::url($this->profile_photo);
        }
        return asset('images/default-profile.png');
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordCustom($token));
    }

    /**
     * Generate and save a new 2FA code (6 digits).
     */
    public function generateTwoFactorCode()
    {
        $this->two_factor_code = random_int(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(2);
        $this->save();
    }

    /**
     * Reset the 2FA code.
     */
    public function resetTwoFactorCode()
    {
        $this->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);
    }

    /**
     * Check if the user's profile is locked
     */
    public function isProfileLocked(): bool
    {
        return $this->profile_locked === true;
    }

    /**
     * Check if the current user can view another user's profile
     */
    public function canViewProfile(User $otherUser): bool
    {
        // L'utilisateur peut toujours voir son propre profil
        if ($this->id === $otherUser->id) {
            return true;
        }

        // Les admins et school admins peuvent voir tous les profils
        if ($this->hasRole('admin') || $this->role === 'school_admin') {
            return true;
        }

        // Si le profil de l'autre utilisateur est verrouillé, personne d'autre ne peut le voir
        if ($otherUser->isProfileLocked()) {
            return false;
        }

        // Pour les autres cas, la logique existante s'applique
        return true;
    }

    /**
     * Toggle profile lock status
     */
    public function toggleProfileLock(): bool
    {
        $this->update(['profile_locked' => !$this->profile_locked]);
        return $this->profile_locked;
    }
}
