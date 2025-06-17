<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the existing foreign key constraint if it exists
            $table->dropForeign(['parent_id']);
            
            // Modify the column to reference users table
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            
            // Restore the original constraint
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('parents')
                  ->onDelete('set null');
        });
    }
}; 