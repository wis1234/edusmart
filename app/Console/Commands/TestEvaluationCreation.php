<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\EvaluationType;
use App\Http\Controllers\EvaluationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestEvaluationCreation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:evaluation-creation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test evaluation creation process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Evaluation Creation Process...');
        $this->newLine();

        // Get a teacher user
        $teacher = User::where('role', 'enseignant')->first();
        
        if (!$teacher) {
            $this->error('No teacher found in the system.');
            return 1;
        }

        // Authenticate the teacher
        Auth::login($teacher);
        
        $this->info("Testing with teacher: {$teacher->name} (ID: {$teacher->id})");
        
        // Récupérer le profil enseignant
        $teacherProfile = Teacher::where('user_id', $teacher->id)->first();
        if (!$teacherProfile) {
            $this->error('No teacher profile found.');
            return 1;
        }

        $this->info("Teacher profile ID: {$teacherProfile->id}");
        $this->info("Teacher's school ID: {$teacherProfile->school_id}");
        
        $this->newLine();

        // Test the create method
        $this->info('Testing Create Method:');
        
        $controller = new EvaluationController();
        $request = new Request();
        
        // Simulate the create method
        $evaluationTypes = EvaluationType::all();
        
        $subjects = Subject::where('school_id', $teacherProfile->school_id)
            ->whereIn('id', $teacherProfile->subjects()->pluck('subjects.id'))
            ->orderBy('name')->get();
        
        $classRooms = ClassRoom::where('school_id', $teacherProfile->school_id)
            ->whereIn('id', $teacherProfile->classRooms()->pluck('class_rooms.id'))
            ->orderBy('name')->get();
        
        $teachers = collect([$teacherProfile]);
        
        $this->info("Available subjects: {$subjects->count()}");
        foreach ($subjects as $subject) {
            $this->line("  - Subject ID: {$subject->id}, Name: {$subject->name}");
        }
        
        $this->info("Available classes: {$classRooms->count()}");
        foreach ($classRooms as $class) {
            $this->line("  - Class ID: {$class->id}, Name: {$class->name}");
        }
        
        $this->info("Evaluation types: {$evaluationTypes->count()}");
        foreach ($evaluationTypes as $type) {
            $this->line("  - Type ID: {$type->id}, Name: {$type->name}");
        }
        
        $this->newLine();

        // Test if the data would pass validation
        $this->info('Testing Validation with Sample Data:');
        
        $sampleData = [
            'subject_id' => $subjects->first()->id,
            'class_room_id' => $classRooms->first()->id,
            'evaluation_type_id' => $evaluationTypes->first()->id,
            'academic_year' => '2024-2025',
            'term' => 'Term 1',
            'evaluation_date' => date('Y-m-d'),
            'total_marks' => 100,
            'passing_marks' => 50,
            'notes' => 'Test evaluation'
        ];
        
        $this->info("Sample data:");
        foreach ($sampleData as $key => $value) {
            $this->line("  - {$key}: {$value}");
        }
        
        $this->newLine();
        
        // Test if the data would pass validation by calling store method
        try {
            $request = new Request($sampleData);
            $controller->store($request);
            $this->info("✓ Evaluation created successfully!");
        } catch (\Exception $e) {
            $this->error("✗ Validation failed: " . $e->getMessage());
            
            // Check if it's a validation error
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $this->error("Validation errors:");
                foreach ($e->errors() as $field => $errors) {
                    foreach ($errors as $error) {
                        $this->error("  - {$field}: {$error}");
                    }
                }
            }
        }

        $this->newLine();
        $this->info('Test completed!');
        
        return 0;
    }
} 