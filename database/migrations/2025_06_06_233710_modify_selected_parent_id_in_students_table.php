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
            $table->dropColumn('selected_parent_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('selected_parent_id')->nullable()->after('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('selected_parent_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('selected_parent_id')->after('parent_id');
        });
    }
};
