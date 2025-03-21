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
        Schema::create('penaksiran_harga', function (Blueprint $table) {
        $table->id('id_penaksiran');
        $table->unsignedBigInteger('id_barang');
        $table->decimal('harga_estimasi', 15, 2);
        $table->date('tanggal_penaksiran');
        $table->text('sumber_referensi')->nullable();
        $table->foreign('id_barang')->references('id_barang')->on('barang_gadai')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penaksiran_harga');
    }
};
