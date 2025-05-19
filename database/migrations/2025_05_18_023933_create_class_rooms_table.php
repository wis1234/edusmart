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
    Schema::create('class_rooms', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Ex : Maternelle 1, CP, CM2
        $table->string('level'); // Maternelle, Primaire
        $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};
