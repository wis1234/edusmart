<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('guardians', function (Blueprint $table) {
            $table->foreignId('student_id')
                ->nullable()
                ->after('user_id')
                ->constrained('students')
                ->nullOnDelete();
                
            $table->foreignId('grade_id')
                ->nullable()
                ->after('student_id')
                ->constrained('student_grades')
                ->nullOnDelete();

                
            $table->foreignId('subject_id')
                ->nullable()
                ->after('grade_id')
                ->constrained('subjects')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('guardians', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['grade_id']);
            $table->dropForeign(['subject_id']);
            
            $table->dropColumn(['student_id', 'grade_id', 'subject_id']);
        });
    }
};
