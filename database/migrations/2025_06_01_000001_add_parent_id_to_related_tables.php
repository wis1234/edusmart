<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToRelatedTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update students table parent_id foreign key to reference parents table
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->foreignId('parent_id')->nullable()->constrained('parents')->onDelete('set null');
        });

        // Add parent_id to subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('parents')->onDelete('set null');
        });

        // Add parent_id to student_grades table
        Schema::table('student_grades', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('parents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert students table parent_id foreign key to users table
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('set null');
        });

        // Remove parent_id from subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });

        // Remove parent_id from student_grades table
        Schema::table('student_grades', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
}
