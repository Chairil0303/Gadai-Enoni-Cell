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
        Schema::create('barang_lelang', function (Blueprint $table) {
            $table->id();
        
            $table->string('no_bon', 14)->unique(); // FK ke barang_gadai
            $table->string('judul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_lelang', 15, 2)->nullable();
            $table->string('foto')->nullable(); // file path
            $table->enum('status_lelang', ['Menunggu Konfirmasi', 'Aktif', 'Tebus', 'Terjual'])->default('Menunggu Konfirmasi');
        
            $table->unsignedBigInteger('id_cabang'); // inherit dari barang_gadai
            $table->timestamp('tanggal_dibuat')->useCurrent();
        
            $table->foreign('no_bon')->references('no_bon')->on('barang_gadai')->onDelete('cascade');
            $table->foreign('id_cabang')->references('id_cabang')->on('cabang')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_lelang');
    }
};
