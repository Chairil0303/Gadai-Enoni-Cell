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
        Schema::create('nasabah', function (Blueprint $table) {
            $table->id('id_nasabah');
            $table->unsignedBigInteger('id_user')->unique(); // Relasi ke tabel users
            $table->string('nama');
            $table->string('nik')->unique();
            $table->text('alamat');
            $table->string('telepon');
            $table->boolean('status_blacklist')->default(false);
            $table->timestamps();

            // Foreign key ke tabel users
            $table->foreign('id_user')->references('id_users')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nasabah');
    }
};