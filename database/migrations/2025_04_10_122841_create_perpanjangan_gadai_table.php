<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('perpanjangan_gadai', function (Blueprint $table) {
            $table->id();
            $table->string('no_bon_lama');
            $table->string('no_bon_baru');
            $table->integer('tenor_baru');
            $table->decimal('harga_gadai_baru', 15, 2);
            $table->decimal('bunga_baru', 15, 2);
            $table->date('tempo_baru');
            $table->timestamps();

            $table->foreign('no_bon_lama')->references('no_bon')->on('barang_gadai')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perpanjangan_gadai');
    }
};
