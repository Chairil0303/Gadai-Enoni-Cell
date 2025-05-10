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
        $table->string('foto_barang')->nullable();
        $table->decimal('harga_lelang', 15, 2)->nullable(); // <--- kolom harga lelang
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
