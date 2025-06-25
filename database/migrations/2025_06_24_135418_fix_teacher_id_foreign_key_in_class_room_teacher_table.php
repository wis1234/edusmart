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
        Schema::table('class_room_teacher', function (Blueprint $table) {
            // Drop the old foreign key that points to the users table
            $table->dropForeign(['teacher_id']);

            // Add the new foreign key that points to the teachers table
            $table->foreign('teacher_id')
                  ->references('id')
                  ->on('teachers')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_room_teacher', function (Blueprint $table) {
            // Drop the new foreign key
            $table->dropForeign(['teacher_id']);

            // Re-add the old foreign key for rollback
            $table->foreign('teacher_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
