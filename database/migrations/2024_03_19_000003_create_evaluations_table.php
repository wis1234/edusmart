<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluation_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Midterm, Final, Quiz
            $table->string('code')->unique();
            $table->integer('weight')->default(1); // Weight for grade calculation
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_room_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluation_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('academic_year');
            $table->string('term'); // e.g., First Term, Second Term
            $table->date('evaluation_date');
            $table->integer('total_marks')->default(100);
            $table->integer('passing_marks')->default(50);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Ensure unique evaluation per subject, class, type, and term
            $table->unique(['subject_id', 'class_room_id', 'evaluation_type_id', 'academic_year', 'term'], 'unique_evaluation');
        });

        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('marks_obtained', 5, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Each student can have only one grade per evaluation
            $table->unique(['evaluation_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_grades');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('evaluation_types');
    }
};
