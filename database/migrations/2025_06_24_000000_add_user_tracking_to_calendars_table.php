<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('calendars', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('week_number')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            $table->json('week_schedule')->nullable()->after('week_number');
        });
    }

    public function down()
    {
        Schema::table('calendars', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by', 'week_schedule']);
        });
    }
}; 