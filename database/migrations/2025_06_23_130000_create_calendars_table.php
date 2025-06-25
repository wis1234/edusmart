<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade')->index();
            $table->foreignId('class_room_id')->constrained()->onDelete('cascade')->index();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade')->index();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade')->index();
            $table->enum('weekday', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])->index();
            $table->time('start_time');
            $table->time('end_time');
            $table->string('academic_year')->index();
            $table->unsignedTinyInteger('week_number')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
            $table->unique([
                'school_id', 'class_room_id', 'subject_id', 'teacher_id', 'weekday', 'start_time', 'end_time', 'academic_year', 'week_number'
            ], 'unique_schedule_slot');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calendars');
    }
}; 