<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('calendars', function (Blueprint $table) {
        $table->id();
        $table->string('academic_year'); // ex: 2024-2025
        $table->string('cohort'); // Trimestre 1, 2, 3
        $table->string('specialty'); // Maternelle 1, CM2, etc.
        $table->string('week'); // Semaine 1, Semaine 2...
        $table->string('time'); // 08:00 - 09:45
        $table->string('monday')->nullable();
        $table->string('tuesday')->nullable();
        $table->string('wednesday')->nullable();
        $table->string('thursday')->nullable();
        $table->string('friday')->nullable();
        $table->string('saturday')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};
