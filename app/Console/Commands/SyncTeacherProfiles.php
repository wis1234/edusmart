<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Teacher;
use App\Models\School;

class SyncTeacherProfiles extends Command
{
    protected $signature = 'sync:teacher-profiles';
    protected $description = 'Synchronise tous les utilisateurs enseignants avec la table teachers';

    public function handle()
    {
        $this->info('Synchronisation des profils enseignants...');
        $countCreated = 0;
        $countAlready = 0;
        $teachers = User::where('role', 'enseignant')->get();
        foreach ($teachers as $user) {
            $profile = Teacher::where('user_id', $user->id)->first();
            if ($profile) {
                $countAlready++;
                continue;
            }
            // Essayer de deviner l'école de l'enseignant (par la première classe ou matière liée, sinon null)
            $schoolId = null;
            if ($user->classRooms()->exists()) {
                $schoolId = $user->classRooms()->first()->school_id;
            } elseif ($user->subjects()->exists()) {
                $schoolId = $user->subjects()->first()->school_id;
            } else {
                $schoolId = School::first()?->id;
            }
            Teacher::create([
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'specialization' => null,
            ]);
            $this->line("Profil enseignant créé pour {$user->first_name} {$user->last_name} (user_id: {$user->id}, school_id: {$schoolId})");
            $countCreated++;
        }
        $this->info("Profils déjà existants : {$countAlready}");
        $this->info("Profils créés : {$countCreated}");
        $this->info('Synchronisation terminée !');
        return 0;
    }
} 