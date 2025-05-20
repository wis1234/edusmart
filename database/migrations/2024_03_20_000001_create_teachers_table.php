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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_firstname');
            $table->string('teacher_lastname')->nullable();
            $table->string('teacher_email')->unique();
            $table->string('teacher_phone');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->text('address');
            $table->string('grade');
            $table->string('speciality');
            $table->string('subject_title');
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->string('profile_photo')->nullable();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        // Create pivot table for teacher-classroom relationship with subject
        Schema::create('class_room_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_room_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->year('year');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->json('days_of_week')->nullable();
            $table->timestamps();

            $table->unique(['teacher_id', 'class_room_id', 'subject_id', 'year'], 'unique_teacher_class_subject_year');
        });

        // Update foreign key in evaluations table to reference teachers instead of users
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });

        // Add foreign key to student_grades table for teacher relationship
        Schema::table('student_grades', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::dropIfExists('class_room_teacher');
        Schema::dropIfExists('teachers');
    }
};
