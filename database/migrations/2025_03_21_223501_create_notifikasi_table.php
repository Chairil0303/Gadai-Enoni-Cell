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
        Schema::create('notifikasi', function (Blueprint $table) {
        $table->id('id_notifikasi');
        $table->unsignedBigInteger('id_nasabah');
        $table->string('jenis_notifikasi');
        $table->text('isi_pesan');
        $table->boolean('status_kirim')->default(false);
        $table->dateTime('tanggal_kirim');
        $table->foreign('id_nasabah')->references('id_nasabah')->on('nasabah')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
