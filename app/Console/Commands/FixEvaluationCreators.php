<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Evaluation;
use App\Models\User;

class FixEvaluationCreators extends Command
{
    protected $signature = 'fix:evaluation-creators';
    protected $description = 'Fix evaluations that have no created_by by assigning them to the teacher';

    public function handle()
    {
        $this->info('Fixing evaluation creators...');
        
        $evaluations = Evaluation::whereNull('created_by')->get();
        $fixed = 0;
        
        foreach ($evaluations as $evaluation) {
            if ($evaluation->teacher_id) {
                // Trouver l'utilisateur correspondant au teacher_id
                $user = User::where('id', $evaluation->teacher_id)->first();
                if ($user) {
                    $evaluation->update(['created_by' => $user->id]);
                    $this->line("Fixed evaluation {$evaluation->id}: assigned to {$user->first_name} {$user->last_name}");
                    $fixed++;
                } else {
                    $this->warn("Evaluation {$evaluation->id}: teacher_id {$evaluation->teacher_id} not found in users table");
                }
            } else {
                $this->warn("Evaluation {$evaluation->id}: no teacher_id found");
            }
        }
        
        $this->info("Fixed {$fixed} evaluations");
        $this->info('Done!');
        
        return 0;
    }
} 