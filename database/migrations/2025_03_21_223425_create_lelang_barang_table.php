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
        Schema::create('lelang_barang', function (Blueprint $table) {
        $table->id('id_lelang');
        $table->unsignedBigInteger('id_barang');
        $table->date('tanggal_lelang');
        $table->decimal('harga_awal', 15, 2);
        $table->enum('status_penjualan', ['Belum Terjual', 'Terjual']);
        $table->foreign('id_barang')->references('id_barang')->on('barang_gadai')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lelang_barang');
    }
};
