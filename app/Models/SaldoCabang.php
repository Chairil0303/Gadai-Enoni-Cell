<?php

// app/Models/SaldoCabang.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaldoCabang extends Model
{
    protected $table = 'saldo_cabang';
    protected $fillable = ['id_cabang', 'saldo_awal', 'saldo_saat_ini'];

    public function saldoCabang()
    {
        return $this->hasOne(SaldoCabang::class, 'id_cabang', 'id_cabang');
    }

}


