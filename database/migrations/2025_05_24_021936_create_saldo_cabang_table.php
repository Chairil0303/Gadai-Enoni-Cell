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
        Schema::create('saldo_cabang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cabang')->unique();
            $table->decimal('saldo_awal', 15, 2);
            $table->decimal('saldo_saat_ini', 15, 2);
            $table->timestamps();
        
            $table->foreign('id_cabang')->references('id_cabang')->on('cabang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_cabang');
    }
};
