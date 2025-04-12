<?php
// database/migrations/2025_04_01_000000_create_transaksi_tebus_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi_tebus', function (Blueprint $table) {
            $table->id('id_transaksi_tebus');
            $table->unsignedBigInteger('id_cabang');
            $table->string('no_bon', 50);
            $table->unsignedBigInteger('id_nasabah');
            $table->date('tanggal_tebus');
            $table->decimal('jumlah_pembayaran', 15, 2);
            $table->enum('status', ['Berhasil', 'Gagal'])->default('Berhasil');
            $table->timestamps();


            $table->foreign('id_cabang')->references('id_cabang')->on('cabang')->onDelete('cascade');
            $table->foreign('no_bon')->references('no_bon')->on('barang_gadai')->onDelete('cascade');
            $table->foreign('id_nasabah')->references('id_nasabah')->on('nasabah')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_tebus');
    }
};
