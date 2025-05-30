<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
    {
        Schema::create('lelang', function (Blueprint $table) {
            $table->id();
            $table->string('barang_gadai_no_bon');
            $table->foreign('barang_gadai_no_bon')->references('no_bon')->on('barang_gadai')->onDelete('cascade');

            $table->text('kondisi_barang');
            $table->text('keterangan')->nullable();
            $table->json('foto_barang')->nullable(); // <-- Multiple Foto
            $table->decimal('harga_lelang', 15, 2)->nullable();
            $table->enum('status', ['Aktif', 'Tebus'])->default('Aktif');
            $table->timestamps();
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lelangs');
    }
};
