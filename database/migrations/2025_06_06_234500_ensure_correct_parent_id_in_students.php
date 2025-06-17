<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // First, drop any existing foreign key constraints on parent_id
            $table->dropForeign(['parent_id']);
            
            // Then modify the column to be nullable and properly reference users table
            $table->foreignId('parent_id')->nullable()->change();
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
            $table->foreignId('parent_id')->nullable()->change();
        });
    }
}; 