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
        Schema::table('video_call_participants', function (Blueprint $table) {
            $table->boolean('is_screen_sharing')->default(false);
            $table->string('screen_stream_id')->nullable();
            $table->timestamp('screen_share_started_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('video_call_participants', function (Blueprint $table) {
            $table->dropColumn(['is_screen_sharing', 'screen_stream_id', 'screen_share_started_at']);
        });
    }
}; 