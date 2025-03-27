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
        if (!Schema::hasTable('penaksiran_harga')) {
        Schema::create('penaksiran_harga', function (Blueprint $table) {
            $table->id('id_penaksiran');
            $table->bigInteger('no_bon')->unsigned();
            $table->decimal('harga_estimasi', 15, 2);
            $table->date('tanggal_penaksiran');
            $table->text('sumber_referensi')->nullable();
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penaksiran_harga');
    }
};
