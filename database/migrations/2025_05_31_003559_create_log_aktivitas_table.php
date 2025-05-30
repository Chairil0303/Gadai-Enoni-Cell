<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogAktivitasTable extends Migration
{
    public function up()
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id('id_log');

            $table->unsignedBigInteger('id_users');
            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('id_cabang')->nullable();
            $table->foreign('id_cabang')->references('id_cabang')->on('cabang')->onDelete('set null');

            $table->string('kategori'); // transaksi, auth, user_mgmt, dll
            $table->string('aksi'); // contoh: login, tebus, ubah_data, dll
            $table->text('deskripsi')->nullable();

            $table->json('data_lama')->nullable();
            $table->json('data_baru')->nullable();

            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('waktu_aktivitas')->useCurrent();

            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_aktivitas');
    }
}
