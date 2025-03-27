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
            Schema::create('transaksi_gadai', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_nasabah');
            $table->unsignedBigInteger('no_bon'); // Tambahkan kolom ini sebelum foreign key
            $table->date('tanggal_gadai');
            $table->decimal('jumlah_pinjaman', 15, 2);
            $table->decimal('bunga', 5, 2);
            $table->date('jatuh_tempo');

            $table->foreign('id_nasabah')->references('id_nasabah')->on('nasabah')->onDelete('cascade');
            $table->foreign('no_bon')->references('no_bon')->on('barang_gadai')->onDelete('cascade');
            $table->timestamps();
        });

        }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_gadai');
    }
};
