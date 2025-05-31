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
        Schema::table('pending_payments', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('status');
            $table->string('redirect_url')->nullable()->after('snap_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pending_payments', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'redirect_url']);
        });
    }
};
