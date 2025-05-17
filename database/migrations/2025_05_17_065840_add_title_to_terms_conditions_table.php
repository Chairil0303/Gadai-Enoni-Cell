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
        Schema::table('terms_conditions', function (Blueprint $table) {
            $table->string('title')->default('Syarat & Ketentuan Pengambilan Barang');
        });
    }

    public function down()
    {
        Schema::table('terms_conditions', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }


    
};
