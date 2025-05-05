<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_transaksi'); // ex: 'terima_gadai', 'perpanjang_gadai'
            $table->enum('arah', ['masuk', 'keluar']); // arah uang
            $table->bigInteger('nominal'); // jumlah uang
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
