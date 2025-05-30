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
        Schema::create('transaksi_terima_gadai', function (Blueprint $table) { // Ganti nama schema di sini
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_nasabah');
            $table->string('no_bon', 50);  // ganti type no_bon jadi string
            $table->foreign('id_user')->references('id_users')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('transaksi_terima_gadai'); // Ganti nama schema di sini juga
    }
};