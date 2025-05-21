<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('guardian_id')
                ->nullable()
                ->after('school_id')
                ->constrained('guardians')
                ->nullOnDelete();
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->foreignId('guardian_id')
                ->nullable()
                ->after('teacher_id')
                ->constrained('guardians')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['guardian_id']);
            $table->dropColumn('guardian_id');
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropForeign(['guardian_id']);
            $table->dropColumn('guardian_id');
        });
    }
};
