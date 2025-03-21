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
        Schema::create('session_nasabah', function (Blueprint $table) {
        $table->id('id_session');
        $table->unsignedBigInteger('id_nasabah');
        $table->dateTime('waktu_login');
        $table->dateTime('waktu_logout')->nullable();
        $table->boolean('status_session');
        $table->foreign('id_nasabah')->references('id_nasabah')->on('nasabah')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_nasabah');
    }
};
