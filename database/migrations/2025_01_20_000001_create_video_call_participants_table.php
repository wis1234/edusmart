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
        Schema::create('video_call_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_call_id')->constrained('video_calls')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['host', 'participant', 'guest'])->default('participant');
            $table->enum('status', ['invited', 'joined', 'left', 'declined'])->default('invited');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->boolean('is_muted')->default(false);
            $table->boolean('is_video_off')->default(false);
            $table->json('permissions')->nullable(); // Permissions spécifiques (modérateur, etc.)
            $table->timestamps();
            
            $table->unique(['video_call_id', 'user_id']);
            $table->index(['user_id', 'status']);
            $table->index(['video_call_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_call_participants');
    }
}; 