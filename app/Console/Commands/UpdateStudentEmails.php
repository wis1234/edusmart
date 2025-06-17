<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Console\Command;

class UpdateStudentEmails extends Command
{
    protected $signature = 'students:update-emails';
    protected $description = 'Update student emails from associated user records';

    public function handle()
    {
        $students = Student::whereNull('email')->get();
        $bar = $this->output->createProgressBar(count($students));
        $bar->start();

        foreach ($students as $student) {
            // Try to get email from user record
            if ($student->user && $student->user->email) {
                $student->email = $student->user->email;
            } else {
                // Generate a placeholder email using student's name and admission number
                $safeName = strtolower(str_replace(' ', '.', $student->first_name . '.' . $student->last_name));
                $student->email = $safeName . '.' . $student->admission_number . '@student.edusmart.com';
            }
            $student->save();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Student emails have been updated successfully!');
    }
} 