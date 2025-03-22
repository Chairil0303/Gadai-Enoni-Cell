<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->unsignedBigInteger('id_transaksi')->nullable();
            $table->unsignedBigInteger('id_lelang')->nullable();
            $table->string('tipe_laporan'); // contoh: 'Keuangan', 'Barang Gadai'
            $table->text('keterangan')->nullable();
            $table->decimal('jumlah', 15, 2)->nullable();
            $table->date('tanggal_laporan');
            $table->timestamps();

            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi_gadai')->onDelete('set null');
            $table->foreign('id_lelang')->references('id_lelang')->on('lelang_barang')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
