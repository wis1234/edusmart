<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            if (!Schema::hasColumn('activities', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('related_type');
            }
            if (!Schema::hasColumn('activities', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }
        });
    }

    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'user_agent']);
        });
    }
}; 