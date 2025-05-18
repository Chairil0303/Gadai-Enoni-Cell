<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('no_bon');
            $table->unsignedBigInteger('id_nasabah');
            $table->unsignedBigInteger('id_cabang');
            $table->enum('jenis_transaksi', ['terima', 'tebus', 'perpanjang']);
            $table->decimal('jumlah_transaksi', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_transaksi');
            $table->timestamps();

            $table->foreign('no_bon')->references('no_bon')->on('barang_gadai')->onDelete('cascade');
            $table->foreign('id_nasabah')->references('id_nasabah')->on('nasabah')->onDelete('cascade');
            $table->foreign('id_cabang')->references('id_cabang')->on('cabang')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}