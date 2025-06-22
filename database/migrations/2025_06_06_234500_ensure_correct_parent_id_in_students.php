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
        Schema::table('students', function (Blueprint $table) {
            // Drop the old foreign key and column if exists
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
        Schema::table('students', function (Blueprint $table) {
            // Add the correct parent_id referencing parents(id)
            $table->foreignId('parent_id')->nullable()->constrained('parents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }
}; 