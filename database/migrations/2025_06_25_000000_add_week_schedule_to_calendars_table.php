<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('calendars', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['weekday', 'start_time', 'end_time']);
            
            // Add new JSON column for week schedule
            $table->json('week_schedule')->nullable();
            
            // Drop the old unique constraint
            $table->dropUnique('unique_schedule_slot');
            
            // Add new unique constraint
            $table->unique([
                'school_id', 'class_room_id', 'subject_id', 'teacher_id', 'academic_year', 'week_number'
            ], 'unique_schedule');
        });
    }

    public function down()
    {
        Schema::table('calendars', function (Blueprint $table) {
            // Drop new column
            $table->dropColumn('week_schedule');
            
            // Restore old columns
            $table->enum('weekday', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])->index();
            $table->time('start_time');
            $table->time('end_time');
            
            // Drop new unique constraint
            $table->dropUnique('unique_schedule');
            
            // Restore old unique constraint
            $table->unique([
                'school_id', 'class_room_id', 'subject_id', 'teacher_id', 'weekday', 'start_time', 'end_time', 'academic_year', 'week_number'
            ], 'unique_schedule_slot');
        });
    }
}; 