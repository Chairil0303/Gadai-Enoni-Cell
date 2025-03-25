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
            $table->id('id_barang');
            $table->unsignedBigInteger('id_nasabah');
            $table->string('nama_barang');
            $table->text('deskripsi')->nullable();
            $table->string('imei')->nullable(); // Menambahkan kolom IMEI
            $table->enum('tenor', ['7', '14', '30'])->default('7'); // Menambahkan tenor
            $table->date('tempo'); // Menambahkan tanggal jatuh tempo
            $table->integer('telat')->default(0); // Menambahkan jumlah hari keterlambatan
            $table->decimal('harga_gadai', 15, 2); // Menambahkan harga gadai
            $table->enum('status', ['Tergadai', 'Ditebus', 'Dilelang']);
            $table->unsignedBigInteger('id_kategori')->nullable();
            
            $table->foreign('id_nasabah')->references('id_nasabah')->on('nasabah')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_barang')->onDelete('set null');
            
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
