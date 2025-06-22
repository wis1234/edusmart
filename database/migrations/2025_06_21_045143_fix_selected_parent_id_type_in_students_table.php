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
            // Change selected_parent_id from string to unsignedBigInteger
            $table->unsignedBigInteger('selected_parent_id')->nullable()->change();
            
            // Add foreign key constraint
            $table->foreign('selected_parent_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['selected_parent_id']);
            
            // Change back to string
            $table->string('selected_parent_id')->nullable()->change();
        });
    }
};
