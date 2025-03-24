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
        Schema::table('barang_gadai', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->after('id_barang')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_gadai', function (Blueprint $table) {
            //
        });
    }
};
