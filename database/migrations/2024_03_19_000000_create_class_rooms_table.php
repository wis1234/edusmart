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
        Schema::create('class_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('grade_level');
            $table->string('section', 8);
            $table->string('academic_year');
            $table->integer('capacity')->default(30);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('days_of_week')->nullable();
            $table->string('room_number')->nullable();
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unique(['school_id', 'grade_level', 'section', 'academic_year'], 'unique_class_in_school');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};
