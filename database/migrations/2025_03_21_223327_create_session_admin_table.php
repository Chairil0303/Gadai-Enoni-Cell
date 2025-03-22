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
        Schema::create('session_admin', function (Blueprint $table) {
        $table->id('id_session');
        $table->unsignedBigInteger('id_users');
        $table->dateTime('waktu_login');
        $table->dateTime('waktu_logout')->nullable();
        $table->boolean('status_session');
        $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_admin');
    }
};
