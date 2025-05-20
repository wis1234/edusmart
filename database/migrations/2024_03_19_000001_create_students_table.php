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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_room_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('admission_number')->unique();
            $table->string('roll_number')->nullable();
            $table->date('admission_date');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('blood_group')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->json('medical_conditions')->nullable();
            $table->string('academic_year');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            // Ensure roll number is unique within a class and academic year
            $table->unique(['class_room_id', 'roll_number', 'academic_year'], 'student_roll_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
