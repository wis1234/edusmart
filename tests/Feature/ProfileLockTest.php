<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileLockTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_cannot_view_locked_profile()
    {
        // Créer un admin
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Créer un enseignant avec profil verrouillé
        $teacherUser = User::factory()->create([
            'role' => 'enseignant',
            'profile_locked' => true
        ]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        
        // Créer un autre enseignant qui essaie de voir le profil
        $otherTeacherUser = User::factory()->create(['role' => 'enseignant']);
        
        $this->actingAs($otherTeacherUser)
            ->get(route('teachers.show', $teacher))
            ->assertStatus(403)
            ->assertSee('This user has locked his profile.');
    }

    public function test_admin_can_view_locked_profile()
    {
        // Créer un enseignant avec profil verrouillé
        $teacherUser = User::factory()->create([
            'role' => 'enseignant',
            'profile_locked' => true
        ]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        
        // Créer un admin
        $admin = User::factory()->create(['role' => 'admin']);
        
        $this->actingAs($admin)
            ->get(route('teachers.show', $teacher))
            ->assertStatus(200);
    }

    public function test_school_admin_can_view_locked_profile()
    {
        // Créer un enseignant avec profil verrouillé
        $teacherUser = User::factory()->create([
            'role' => 'enseignant',
            'profile_locked' => true
        ]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        
        // Créer un school admin
        $schoolAdmin = User::factory()->create(['role' => 'admin']);
        
        $this->actingAs($schoolAdmin)
            ->get(route('teachers.show', $teacher))
            ->assertStatus(200);
    }

    public function test_unlocked_profile_can_be_viewed_by_anyone()
    {
        // Créer un enseignant avec profil non verrouillé
        $teacherUser = User::factory()->create([
            'role' => 'enseignant',
            'profile_locked' => false
        ]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        
        // Créer un autre enseignant
        $otherTeacherUser = User::factory()->create(['role' => 'enseignant']);
        
        $this->actingAs($otherTeacherUser)
            ->get(route('teachers.show', $teacher))
            ->assertStatus(200);
    }

    public function test_user_can_view_own_locked_profile()
    {
        // Créer un enseignant avec profil verrouillé
        $teacherUser = User::factory()->create([
            'role' => 'enseignant',
            'profile_locked' => true
        ]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        
        $this->actingAs($teacherUser)
            ->get(route('teachers.show', $teacher))
            ->assertStatus(200);
    }
}
