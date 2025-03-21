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
        Schema::create('pembayaran', function (Blueprint $table) {
        $table->id('id_pembayaran');
        $table->unsignedBigInteger('id_transaksi');
        $table->decimal('jumlah_bayar', 15, 2);
        $table->date('tanggal_bayar');
        $table->decimal('denda_keterlambatan', 15, 2)->default(0);
        $table->boolean('status_pelunasan')->default(false);
        $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi_gadai')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
