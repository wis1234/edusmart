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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->integer('credits')->default(0);
            $table->string('level')->nullable(); // e.g., beginner, intermediate, advanced
            $table->integer('hours_per_week')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Pivot table for teachers and subjects
        // Schema::create('subject_teacher', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('subject_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('user_id')->constrained()->onDelete('cascade'); // teachers are users
        //     $table->foreignId('class_room_id')->constrained()->onDelete('cascade');
        //     $table->string('academic_year');
        //     $table->timestamps();
            
        //     // Using a shorter name for the unique constraint
        //     $table->unique(
        //         ['subject_id', 'user_id', 'class_room_id', 'academic_year'],
        //         'subject_teacher_unique'
        //     );
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_teacher');
        Schema::dropIfExists('subjects');
    }
};
