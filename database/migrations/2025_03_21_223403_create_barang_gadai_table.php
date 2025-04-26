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

    Schema::create('barang_gadai', function (Blueprint $table) {
    $table->string('no_bon', 14)->primary();  // PRIMARY KEY ganti jadi string
    $table->string('no_bon_lama')->nullable();
    $table->unsignedBigInteger('id_nasabah');
    $table->unsignedBigInteger('id_cabang')->nullable();
    $table->unsignedBigInteger('id_bunga_tenor')->nullable();
    $table->string('nama_barang');
    $table->text('deskripsi')->nullable();
    $table->string('imei');
    $table->date('tempo');
    $table->integer('telat')->default(0);
    $table->decimal('harga_gadai', 15, 2);
    $table->decimal('bunga', 10, 2)->default(0);
    $table->enum('status', ['Tergadai', 'Ditebus', 'Dilelang','Diperpanjang'])->default('Tergadai');
    $table->unsignedBigInteger('id_kategori')->nullable();
    $table->foreign('id_nasabah')->references('id_nasabah')->on('nasabah')->onDelete('cascade');
    $table->foreign('id_kategori')->references('id_kategori')->on('kategori_barang')->onDelete('set null');
    $table->foreign('id_cabang')->references('id_cabang')->on('cabang')->onDelete('set null');
    $table->foreign('id_bunga_tenor')->references('id')->on('bunga_tenor')->onDelete('set null');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_gadai');
    }
};
// migration barang gadai
